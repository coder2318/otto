<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

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
            'recordings' => $this->whenLoaded('recordings', fn () => $this->resource->recordings->map(
                fn (Media $record) => [
                    'id' => $record->id,
                    'url' => $record->getUrl(), // $record->getTemporaryUrl(now()->addMinutes(5)),
                    'name' => $record->file_name,
                    'size' => $record->size,
                    'transcribed' => $record->hasCustomProperty('transcribed'),
                ]
            )),
        ]);
    }
}
