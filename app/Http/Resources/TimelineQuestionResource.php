<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TimelineQuestionResource extends JsonResource
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
            'covers' => $this->whenLoaded('covers', fn () => $this->resource->covers->map(fn ($cover) => $cover->getUrl())),
        ]);
    }
}
