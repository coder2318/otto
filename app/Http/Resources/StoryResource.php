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
        $data = array_merge(parent::toArray($request), [
            'activeUserCoverTemplate' => $this->whenLoaded('activeUserCoverTemplate', fn() => $this->activeUserCoverTemplate ? BookUserCoverTemplateResource::make($this->activeUserCoverTemplate) : null),
            'book' => $this->whenLoaded('book', fn() => $this->resource->book->getTemporaryUrl(now()->addHour())),
            'book_preview' => $this->whenLoaded('book_preview', fn() => $this->resource->book_preview->getTemporaryUrl(now()->addHour())),
            'book_cover' => $this->whenLoaded('book_cover', fn() => $this->resource->book_cover->getTemporaryUrl(now()->addHour())),
            'pages' => $this->whenAppended('pages', fn() => $this->resource->pages),
            'words' => $this->whenAppended('words', fn() => $this->resource->words),
            'progress' => $this->whenAppended('progress', fn() => $this->resource->progress),
        ]);

        unset($data['active_user_cover_template']);

        return $data;
    }
}
