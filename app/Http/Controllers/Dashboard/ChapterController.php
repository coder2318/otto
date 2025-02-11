<?php

namespace App\Http\Controllers\Dashboard;

use App\Data\Chapter\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chapters\ChaptersRequest;
use App\Http\Requests\Chapters\StoreChapterRequest;
use App\Http\Requests\Chapters\TranscribeRequest;
use App\Http\Requests\Chapters\UpdateChapterRequest;
use App\Http\Resources\ChapterResource;
use App\Http\Resources\PromptResource;
use App\Http\Resources\QuestionsChaptersResource;
use App\Http\Resources\StoryResource;
use App\Http\Resources\TimelineQuestionResource;
use App\Http\Resources\TimelineResource;
use App\Jobs\RegenerateBookPreview;
use App\Models\Chapter;
use App\Models\Guest;
use App\Models\Media;
use App\Models\Prompt;
use App\Models\Story;
use App\Models\TimelineQuestion;
use App\Notifications\GuestChapterInviteNotification;
use App\Services\AiService;
use App\Services\MediaService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Http\Controllers\Traits\ChapterControllerTrait;

class ChapterController extends Controller
{
    use ChapterControllerTrait;

    public function __construct()
    {
        $this->authorizeResource(Chapter::class, 'chapter');
        $this->middleware('can:update,chapter')
            ->only(['write', 'attachments', 'transcribe', 'deleteAttachments', 'record', 'upload', 'enhance', 'finish']);
    }

    public function index(Story $story, ChaptersRequest $request)
    {
        $questions = fn() => QuestionsChaptersResource::collection(
            $request->questions($story)
                ->simplePaginate(6)
                ->appends($request->query())
        );

        if ($request->wantsJson()) {
            return $questions();
        }

        return Inertia::render('Dashboard/Chapters/Index', [
            'story' => fn() => StoryResource::make($story->load('cover')->append('pages')),
            'questions' => $questions,
            'timelines' => fn() => TimelineResource::collection(
                $story->storyType->timelines()->get(['id', 'title'])
            ),
        ]);
    }

    public function write(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Write', [
            'chapter' => fn() => ChapterResource::make($chapter->load('images')),
            'transcriptions' => fn() => session('transcriptions'),
        ]);
    }

    public function attachments(Chapter $chapter, Request $request)
    {
        $chapter->onlyUntranscribed = $request->has('onlyUntranscribed');

        return Inertia::render('Dashboard/Chapters/Attachments', [
            'chapter' => fn() => ChapterResource::make($chapter->load('attachments')),
            'onlyUntranscribed' => $request->has('onlyUntranscribed')
        ]);
    }

    public function transcribe(Chapter $chapter, TranscribeRequest $request, MediaService $service)
    {
        $attachments = $request->validated('attachments');
        $media = $chapter->attachments()->whereIn('id', $attachments)->get()
            ->sortBy(fn(Model $a) => array_search($a->getKey(), $attachments));

        $transcriptions = null;

        /** @var \Illuminate\Support\Collection<int,\App\Models\Media> $media */
        foreach ($media as $record) {
            if ($transcription = $service->transcribe($record)) {
                $transcriptions[$record->file_name] = $transcription;
            }
        }

        return redirect()->route('dashboard.chapters.write', compact('chapter'))->with('transcriptions', $transcriptions);
    }

    public function deleteAttachments(Chapter $chapter, Media $attachment, Request $request, $onlyUntranscribed = null)
    {
        $attachment->delete();

        $queryParams = [];

        if ($onlyUntranscribed === 'onlyUntranscribed') {
            $queryParams['onlyUntranscribed'] = true;
        }

        return redirect()->route('dashboard.chapters.attachments', array_merge(compact('chapter'), $queryParams))
            ->with('message', 'Attachment deleted successfully!');
    }

    public function record(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Record', [
            'chapter' => fn() => ChapterResource::make($chapter->load('question.covers')),
        ]);
    }

    public function upload(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Upload', [
            'chapter' => fn() => ChapterResource::make($chapter),
        ]);
    }

    //Enhanced with OttoStory button
    public function process(Chapter $chapter, AiService $service, Request $request)
    {
        return new StreamedResponse(function () use ($chapter, $service, $request) {
            $request->validate([
                'tone_id' => ['sometimes', 'nullable', 'numeric', 'exists:prompts,id'],
                'perspective_id' => ['sometimes', 'nullable', 'numeric', 'exists:prompts,id'],
            ]);

            $tone = $request->tone_id ? Prompt::where('id', $request->input('tone_id'))->value('content') : null;
            $perspective = $request->perspective_id ? Prompt::where('id', $request->input('perspective_id'))->value('content') : null;
            $prompt = collect([$tone, $perspective])->filter()->join(PHP_EOL . PHP_EOL);

            foreach (
                $service->chatEditStreamed(
                    $chapter->content,
                    $chapter->title,
                    empty($prompt) ? null : $prompt,
                    Auth::user()->full_name, // @phpstan-ignore-line
                ) as $chunk
            ) {
                if (connection_aborted()) {
                    return;
                }

                echo $chunk;

                ob_flush();
                flush();
            }
        }, headers: ['X-Accel-Buffering' => 'no']);
    }

    public function enhance(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Enhance', [
            'chapter' => fn() => ChapterResource::make($chapter->load('cover')),
            'prompts' => fn() => PromptResource::collection(
                Prompt::all(['id', 'title', 'description', 'icon', 'perspective'])
            ),
        ]);
    }

    public function finish(Chapter $chapter)
    {
        $chapter->update([
            'status' => Status::PUBLISHED,
        ]);

        if ($chapter->wasChanged('status')) {
            dispatch(new RegenerateBookPreview($chapter->story));
        }

        return Inertia::render('Dashboard/Chapters/Finish', [
            'chapter' => fn() => ChapterResource::make($chapter),
            'questions' => fn() => TimelineQuestionResource::collection((
                $chapter?->timeline?->questions()->where('id', '!=', $chapter?->timeline_question_id) ?? // @phpstan-ignore-line
                TimelineQuestion::query()
            )->inRandomOrder()->limit(3)->get()),
        ]);
    }

    public function congratulation(Chapter $chapter)
    {
        $chapter->update([
            'status' => Status::DRAFT,
        ]);

        if ($chapter->wasChanged('status')) {
            dispatch(new RegenerateBookPreview($chapter->story));
        }

        return Inertia::render('Dashboard/Chapters/Congratulation', [
            'chapter' => fn() => ChapterResource::make($chapter),
        ]);
    }

    public function create(Story $story, ?TimelineQuestion $question = null)
    {
        if ($question) {
            /** @var Chapter */
            $chapter = $story->chapters()->firstOrCreate(['timeline_question_id' => $question->id], [
                'title' => $question->question,
                'timeline_question_id' => $question->id,
                'timeline_id' => $question->timeline_id,
                'status' => Status::UNDONE,
            ]);

            if ($cover = $question->cover) {
                /** @var Media $cover */
                $cover->copy($chapter, 'cover');
            }

            return redirect()->route('dashboard.chapters.edit', compact('chapter'));
        }

        return Inertia::render('Dashboard/Chapters/Create', [
            'story' => fn() => StoryResource::make($story),
            'timelines' => fn() => TimelineResource::collection(
                $story->storyType?->timelines()->get(['id', 'title']) ?? []
            ),
        ]);
    }

    public function invite(Chapter $chapter, Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'name' => 'required',
        ]);

        $guest = Guest::firstOrCreate(
            ['email' => $data['email']],
            ['name' => $data['name']],
        );

        $chapter->update(['guest_id' => $guest->id]);

        $guest->notify(new GuestChapterInviteNotification($chapter));

        return redirect()->back()->with('message', 'Guest invited successfully!');
    }

    public function store(Story $story, StoreChapterRequest $request)
    {
        /** @var Chapter */
        $chapter = $story->chapters()->create(array_merge($request->validated(), [
            'status' => Status::DRAFT,
        ]));

        if ($request->hasFile('cover')) {
            $chapter->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        if ($request->redirect) {
            return redirect()->route($request->redirect, compact('chapter'));
        }

        return redirect()->route('dashboard.chapters.edit', compact('chapter'));
    }

    public function show(Chapter $chapter)
    {
        return Inertia::render('Dashboard/Chapters/Show', [
            'chapter' => fn() => ChapterResource::make($chapter),
        ]);
    }

    public function edit(Chapter $chapter)
    {
        $chapter->load('attachments');
        $chapterResource = ChapterResource::make($chapter);

        return Inertia::render('Dashboard/Chapters/Edit', [
            'chapter' => $chapterResource,
        ]);
    }

    public function update(UpdateChapterRequest $request, Chapter $chapter, MediaService $service)
    {
        $chapter->update($request->validated());

        if ($request->hasFile('cover')) {
            $chapter->cover()->delete();
            $chapter->addMediaFromRequest('cover')->toMediaCollection('cover');
        }

        foreach ($request->validated('attachments') ?? [] as $attachment) {
            $record = $chapter->addMedia($attachment['file'])
                ->withCustomProperties(['mime-type' => $attachment['file']->getMimeType()] + ($attachment['options'] ?? []))
                ->toMediaCollection('attachments', config('media-library.private_disk_name'));

            $source = $attachment['translate']['source'] ?? null;
            $target = $attachment['translate']['target'] ?? null;

            if ($transcription = $service->transcribe($record, $source, $target)) {
                $transcriptions[$record->file_name] = $transcription;
            }
        }

        $addedImages = false;

        foreach ($request->validated('images') ?? [] as $image) {
            $chapter->clearMediaCollection('images');
            $record = $chapter->addMedia($image['file'])
                ->withCustomProperties(['caption' => $image['caption'] ?? null])
                ->toMediaCollection('images', config('media-library.private_disk_name'));

            $addedImages = true;
        }

        if (isset($transcriptions)) {
            Session::flash('transcriptions', $transcriptions);
        }

        if ($chapter->wasChanged(['status', 'content', 'title']) || $addedImages) {
            dispatch(new RegenerateBookPreview($chapter->story));
        }

        if ($redirect = $request->validated('redirect')) {
            $story = $chapter->story;

            return redirect()->route($redirect, compact('chapter', 'story'))->with('message', 'Chapter updated successfully!');
        }

        return redirect()->back()->with('message', 'Chapter updated successfully!');
    }

    public function destroy(Chapter $chapter)
    {
        $chapter->delete();

        dispatch(new RegenerateBookPreview($chapter->story));

        return redirect()->back()->with('message', 'Chapter deleted successfully!');
    }
}
