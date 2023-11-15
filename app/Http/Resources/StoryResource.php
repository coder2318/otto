<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'cover' => $this->whenLoaded('cover', fn () => [
                'url' => $this->resource->cover?->getUrl(),
                'meta' => array_merge(
                    $this->resource->cover?->custom_properties,
                    $this->resource->cover?->media
                        ->mapWithKeys(fn ($media) => [$media->collection_name => $media->getUrl()])
                        ->all() ?? []
                ),
            ]),
            'book' => $this->whenLoaded('book', fn () => $this->resource->book->getTemporaryUrl(now()->addHour())),
            'book_cover' => $this->whenLoaded('book_cover', fn () => $this->resource->book_cover->getTemporaryUrl(now()->addHour())),
        ]);
    }
}
