<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Chapter\Status as ChapterStatus;
use App\Data\Story\Status as StoryStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chapters\TranscribeRequest;
use App\Http\Requests\Chapters\UpdateChapterRequest;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\PromptResource;
use App\Http\Resources\TimelineQuestionResource;
use App\Models\Chapter;
use App\Models\Media;
use App\Models\Prompt;
use App\Models\Story;
use App\Models\TimelineQuestion;
use App\Models\User;
use App\Notifications\DemoFinishedNotification;
use App\Services\AiService;
use App\Services\MediaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Mccarlosen\LaravelMpdf\Facades\LaravelMpdf as Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DemoController extends Controller
{
    protected User $user;

    protected Story $story;

    protected Chapter $chapter;

    protected function data(Request $request, ?TimelineQuestion $question = null)
    {
        $this->user ??= $request->user();

        $this->story ??= $this->user->stories()->firstOrCreate(values: [
            'title' => 'Demo Story',
            'status' => StoryStatus::PENDING,
            'story_type_id' => $question?->timeline?->story_type_id,
        ]);

        $this->chapter ??= $this->story->chapters()->firstOrCreate(values: [
            'title' => $question?->question ?? 'Demo Chapter',
            'status' => ChapterStatus::DRAFT,
            'timeline_question_id' => $question?->id,
            'timeline_id' => $question?->timeline_id,
        ]);

        if ($cover = $question?->cover) {
            /** @var Media $cover */
            $cover->copy($this->chapter, 'cover');
        }

        return [$this->chapter, $this->story];
    }

    protected function hasStory(Request $request)
    {
        return $request->user()->stories()->count() > 0;
    }

    public function index()
    {
        return Inertia::render('Dashboard/Demo/Index', [
            'questions' => TimelineQuestionResource::collection(
                TimelineQuestion::where('is_demo', true)->inRandomOrder()->with('cover')->take(3)->get()
            ),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'question' => ['required', 'exists:timeline_questions,id'],
        ]);

        $this->data($request, TimelineQuestion::find($request->input('question')));

        return redirect()->route('dashboard.demo.record');
    }

    public function record(Request $request)
    {
        [$chapter] = $this->data($request);

        if ($chapter->attachments()->count() > 0) {
            return redirect()->route('dashboard.demo.attachments')->with('status', 'You can not transcribe more than one record for demo!');
        }

        return Inertia::render('Dashboard/Demo/Record', [
            'chapter' => fn () => ChapterResource::make($chapter->load('question.covers')),
        ]);
    }

    public function update(UpdateChapterRequest $request, MediaService $service)
    {
        /** @var Chapter */
        [$chapter] = $this->data($request);
        $chapter->update($request->validated());

        if ($request->hasFile('cover')) {
            $chapter->cover()->delete();
            $chapter->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        foreach ($request->validated('attachments') ?? [] as $attachment) {
            $record = $chapter->addMedia($attachment['file'])
                ->withCustomProperties(['mime-type' => $attachment['file']->getMimeType()] + ($attachment['options'] ?? []))
                ->toMediaCollection('attachments', config('media-library.private_disk_name'));

            if ($transcription = $service->transcribe($record)) {
                $transcriptions[$record->file_name] = $transcription;
            }
        }

        foreach ($request->validated('images') ?? [] as $image) {
            $chapter->clearMediaCollection('images');
            $record = $chapter->addMedia($image['file'])
                ->withCustomProperties(['caption' => $image['caption'] ?? null])
                ->toMediaCollection('images', config('media-library.private_disk_name'));
        }

        if (isset($transcriptions)) {
            Session::flash('transcriptions', $transcriptions);
        }

        if ($redirect = $request->validated('redirect')) {
            return redirect()->route($redirect);
        }

        return redirect()->back()->with('message', 'Chapter updated successfully!');
    }

    public function attachments(Request $request)
    {
        [$chapter] = $this->data($request);

        return Inertia::render('Dashboard/Demo/Attachments', [
            'chapter' => fn () => ChapterResource::make(
                $chapter->load('attachments')
            ),
        ]);
    }

    public function transcribe(TranscribeRequest $request, MediaService $service)
    {
        [$chapter] = $this->data($request);

        /** @var \Illuminate\Support\Collection<int,\App\Models\Media> */
        $media = $chapter->attachments()->whereIn('id', $request->validated('attachments'))->get();

        $transcriptions = null;

        foreach ($media as $record) {
            if ($transcription = $service->transcribe($record)) {
                $transcriptions[$record->file_name] = $transcription;
            }
        }

        return redirect()->route('dashboard.demo.write')->with('transcriptions', $transcriptions);
    }

    public function write(Request $request)
    {
        [$chapter] = $this->data($request);

        return Inertia::render('Dashboard/Demo/Write', [
            'chapter' => fn () => ChapterResource::make($chapter->load('images')),
            'transcriptions' => fn () => session('transcriptions'),
        ]);
    }

    public function deleteAttachments(Media $attachment)
    {
        $attachment->delete();

        return redirect()->route('dashboard.demo.attachments')->with('message', 'Attachment deleted successfully!');
    }

    public function process(Request $request, AiService $service)
    {
        [$chapter] = $this->data($request);

        $user = $request->user();

        abort_unless($request->user()->enhances > 0, 403);

        $user->decrement('enhances');

        return new StreamedResponse(function () use ($chapter, $service, $request) {
            $request->validate([
                'tone_id' => ['sometimes', 'nullable', 'numeric', 'exists:prompts,id'],
                'perspective_id' => ['sometimes', 'nullable', 'numeric', 'exists:prompts,id'],
            ]);

            foreach ($service->chatEditStreamed($chapter->content, $chapter->title) as $chunk) {
                if (connection_aborted()) {
                    return;
                }

                echo $chunk;

                ob_flush();
                flush();
            }
        }, headers: ['X-Accel-Buffering' => 'no']);
    }

    public function enhance(Request $request)
    {
        [$chapter] = $this->data($request);

        $user = Auth::user();

        if ($user->enhances <= 0) {
            return redirect()->back()->with('error', "You've used all your enhances for demo");
        }

        return Inertia::render('Dashboard/Demo/Enhance', [
            'chapter' => fn () => ChapterResource::make($chapter),
            'prompts' => fn () => PromptResource::collection(
                Prompt::all(['id', 'title', 'description', 'icon', 'perspective'])
            ),
        ]);
    }

    public function finish(Request $request)
    {
        [$chapter, $story] = $this->data($request);

        if ($chapter->status !== ChapterStatus::PUBLISHED) {
            $request->user()->notify(new DemoFinishedNotification($story));
        }

        $chapter->update([
            'status' => ChapterStatus::PUBLISHED,
        ]);

        return Inertia::render('Dashboard/Demo/Finish', [
            'chapter' => fn () => ChapterResource::make($chapter),
        ]);
    }

    public function book(Request $request)
    {
        [$chapter, $story] = $this->data($request);

        return Pdf::loadView('pdf.chapter', compact('story', 'chapter'))->stream('demo.pdf');
    }

    public function removeImage(int $imageId, Request $request)
    {
        [$chapter] = $this->data($request);
        $media = $chapter->images()->where('id', $imageId)->firstOrFail();
        $media->delete();

        return redirect()->back()->with('message', 'Image removed successfully!');
    }
}
