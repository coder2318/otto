<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class ChapterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $mediaTypes = [
            'video/webm',
            'audio/webm',
            'audio/wav',
            'audio/mpeg',
            'audio/mpeg3',
            'audio/x-mpeg-3',
            'audio/m4a',
            'audio/mp4',
            'video/mp4',
            'audio/flac',
            'audio/aac',
            'audio/x-wav',
            'audio/x-m4a',
            'audio/mp3',
        ];

        $tempImagesById = [];
        $tempImages = $this->whenLoaded('images', fn() => $this->resource->images->map(
            fn(Media $record) => [
                'id' => $record->id,
                'url' => $record->getTemporaryUrl(now()->addHour()),
                'caption' => $record->getCustomProperty('caption'),
            ],
        ));
        foreach ($tempImages as $image) {
            $tempImagesById[$image['id']] = $image;
        }

        $this->resource->content = preg_replace_callback('/<img[^>]+>/im', function ($matches) use (&$tempImagesById) {
            $imageTeg = $matches[0];

            preg_match('@id="([^"]+)"@', $imageTeg, $match);
            $id = array_pop($match);

            preg_match('@src="([^"]+)"@', $imageTeg, $match);
            $src = array_pop($match);

            if (isset($tempImagesById[$id])) {
                $imageTeg = str_replace($src, $tempImagesById[$id]['url'], $imageTeg);
            }

            return $imageTeg;
        }, $this->resource->content);

        $attachments = $this->whenLoaded('attachments', function () use ($mediaTypes) {
            $attachments = $this->resource->attachments;

            if ($this->resource->onlyUntranscribed) {
                $attachments = $attachments->filter(fn(Media $record) => !$record->hasCustomProperty('transcript') && in_array($record->getCustomProperty('mime-type', $record->mime_type), $mediaTypes));
            }

            return $attachments->map(
                fn(Media $record) => [
                    'id' => $record->id,
                    'url' => $record->getTemporaryUrl(now()->addHour()),
                    'name' => $record->file_name,
                    'size' => $record->size,
                    'transcribed' => $record->hasCustomProperty('transcript'),
                    'is_media' => in_array($record->getCustomProperty('mime-type', $record->mime_type), $mediaTypes),
                    'created_at' => $record->created_at,
                ]
            )->toArray();
        });

        $hasUntranscribedAttachments = $this->resource->attachments
            ? $this->resource->attachments->contains(fn(Media $record) => !$record->hasCustomProperty('transcript') && in_array($record->getCustomProperty('mime-type', $record->mime_type), $mediaTypes))
            : false;

        Log::info(json_encode($attachments));
        return array_merge(parent::toArray($request), [
            'content' => $this->resource->content,
            'cover' => $this->whenLoaded('cover', fn() => $this->resource->cover->getUrl('chapters-list')),
            'attachments' => $attachments ?? [],
            'hasUntranscribedAttachments' => $hasUntranscribedAttachments,
            'images' => $tempImages,
            'guest' => $this->whenLoaded('guest', fn() => GuestResource::make($this->resource->guest)),
            'question' => $this->whenLoaded('question', fn() => TimelineQuestionResource::make($this->resource->question)),
        ]);
    }
}
