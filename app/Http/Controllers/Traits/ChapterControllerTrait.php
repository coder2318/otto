<?php

namespace App\Http\Controllers\Traits;
use App\Http\Requests\Chapters\UpdateChapterRequest;
use App\Models\Chapter;
use App\Models\Media;
use App\Services\AiService;
use App\Services\MediaService;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait ChapterControllerTrait
{

    public function translate(Chapter $chapter, AiService $service, Request $request)
    {
        return new StreamedResponse(function () use ($chapter, $service, $request) {
            $request->validate([
                'content' => ['string', 'nullable'],
                'language' => ['string', 'nullable'],
            ]);

            foreach ($service->translateTextStreamed(
                $chapter->content,
                $request->language,
            ) as $chunk) {
                if (connection_aborted()) {
                    return;
                }

                echo $chunk;

                ob_flush();
                flush();
            }
        }, headers: ['X-Accel-Buffering' => 'no']);
    }

    public function removeImage(Chapter $chapter, int $imageId)
    {
        $media = $chapter->images()->where('id', $imageId)->firstOrFail();
        $media->delete();

        return redirect()->back()->with('message', 'Image removed successfully!');
    }

    public function addImage(UpdateChapterRequest $request, Chapter $chapter)
    {
        if ($image = $request->validated('image')) {
            $response = [
                'error' => true,
                'image' => null,
            ];
            if ($media = Media::find($image['id'])) {
                $mediaCopy = $media->copy($chapter, 'images', config('media-library.private_disk_name'), $media->file_name);
                $response = [
                    'error' => false,
                    'image' => [
                        'id' => $mediaCopy->id,
                        'url' => $mediaCopy->getTemporaryUrl(now()->addHour()),
                        'caption' => $mediaCopy->getCustomProperty('caption'),
                    ],
                ];
            }

            return response()->json($response);
        } else {
            foreach ($request->validated('images') ?? [] as $image) {
                $filePath = $image['file']->getPathname();

                $manager = new \Intervention\Image\ImageManager();
                $tempImage = $manager->make($filePath);
                $tempImage = $tempImage->orientate();
                $tempImage->save($filePath);

                $record = $chapter->addMedia($image['file'])
                    ->withCustomProperties(['caption' => $image['caption'] ?? null])
                    ->toMediaCollection('images', config('media-library.private_disk_name'));

                return response()->json([
                    'image' => [
                        'id' => $record->id,
                        'url' => $record->getTemporaryUrl(now()->addHour()),
                        'caption' => $record->getCustomProperty('caption'),
                    ],
                ]);
            }
        }
    }

    public function uploadAttachments(UpdateChapterRequest $request, Chapter $chapter, MediaService $service)
    {
        $transcriptions = [];

        $params = json_decode(($_POST['filepond'] ?? '{}'));
        $file = $_FILES['filepond'] ?? null;

        if ($file) {
            $uploadedFile = new \Symfony\Component\HttpFoundation\File\UploadedFile($file['tmp_name'], $file['name'], $file['type'], $file['error']);

            $record = $chapter->addMedia($uploadedFile)
                ->toMediaCollection('attachments', config('media-library.private_disk_name'));

            $source = $params->source ?? null;
            $target = $params->target ?? null;

            if ($transcription = $service->transcribe($record, $source, $target)) {
                $transcriptions[$record->file_name] = $transcription;
            }
        }

        if (count($transcriptions)) {
            Session::flash('transcriptions', $transcriptions);
        }
    }

}
