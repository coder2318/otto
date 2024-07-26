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
        $routeName = $request->route()->getName();

        $cover = $this->whenLoaded('cover', fn () => [
            'url' => $this->resource->cover?->getUrl(),
            'meta' => array_merge(
                $this->resource->cover?->custom_properties,
                $this->resource->cover?->media
                    ->mapWithKeys(fn ($media) => [$media->collection_name => $media->getUrl()])
                    ->all() ?? []
            ),
        ]);

        if ($routeName == 'dashboard.stories.cover' || $routeName == 'dashboard.stories.covers') {
            $cover['meta']['front_base64'] = null;
            $cover['meta']['back_base64'] = null;

            $medias = $this->resource->cover?->media ?? [];
            foreach ($medias as $v) {
                if ($v->collection_name == 'front' || $v->collection_name == 'back') {
                    if (in_array($v->conversions_disk, ['s3', 's3-public'])) {
                        $path = $v->getTemporaryUrl(now()->addHour());
                        $cover['meta'][$v->collection_name] = $path;
                    } else {
                        $path = $v->getPath();
                        $cover['meta'][$v->collection_name] = $v->getUrl();
                    }
                    $type = pathinfo($path, PATHINFO_EXTENSION);
                    $data = file_get_contents($path);
                    $base64 = 'data:image/'.$type.';base64,'.base64_encode($data);

                    $cover['meta']["{$v->collection_name}_base64"] = $base64;
                }
            }
        }

        return array_merge(parent::toArray($request), [
            'cover' => $cover,
            'book' => $this->whenLoaded('book', fn () => $this->resource->book->getTemporaryUrl(now()->addHour())),
            'book_preview' => $this->whenLoaded('book_preview', fn () => $this->resource->book_preview->getTemporaryUrl(now()->addHour())),
            'book_cover' => $this->whenLoaded('book_cover', fn () => $this->resource->book_cover->getTemporaryUrl(now()->addHour())),
            'pages' => $this->whenAppended('pages', fn () => $this->resource->pages),
            'words' => $this->whenAppended('words', fn () => $this->resource->words),
            'progress' => $this->whenAppended('progress', fn () => $this->resource->progress),
        ]);
    }
}
