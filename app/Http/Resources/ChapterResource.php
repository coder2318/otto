<?php

namespace App\Http\Resources;

use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapterResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'cover' => $this->whenLoaded('cover', fn () => $this->resource->cover->getUrl()),
            'attachments' => $this->whenLoaded('attachments', fn () => $this->resource->attachments->map(
                fn (Media $record) => [
                    'id' => $record->id,
                    'url' => $record->getTemporaryUrl(now()->addHour()),
                    'name' => $record->file_name,
                    'size' => $record->size,
                    'transcribed' => $record->hasCustomProperty('transcript'),
                    'is_media' => in_array($record->getCustomProperty('mime-type', $record->mime_type), [
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
                    ]),
                    'created_at' => $record->created_at,
                ]
            )),
            'images' => $this->whenLoaded('images', fn () => $this->resource->images->map(
                fn (Media $record) => [
                    'id' => $record->id,
                    'url' => $record->getTemporaryUrl(now()->addHour()),
                    'caption' => $record->getCustomProperty('caption'),
                ],
            )),
            'guest' => $this->whenLoaded('guest', fn () => GuestResource::make($this->resource->guest)),
            'question' => $this->whenLoaded('question', fn () => TimelineQuestionResource::make($this->resource->question)),
        ]);
    }
}
