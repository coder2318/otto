<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BookCoverTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $mediaData = $this->story?->cover?->media->mapWithKeys(function ($media) {
            return [
                "{$media->collection_name}_image_file" => $media->getPath(),
                "{$media->collection_name}_image" => $media->getUrl(),
            ];
        })->all() ?? [];

        if (empty($mediaData['back_image']) && ! empty($data['back_image'])) {
            $data['back_image_file'] = $data['back_image'];
            $data['back_image'] = Storage::disk(config('media-library.private_disk_name'))->temporaryUrl($data['back_image'], now()->addHour());
        } elseif (! empty($mediaData['back_image'])) {
            $data['back_image_file'] = $mediaData['back_image_file'];
            $data['back_image'] = $mediaData['back_image'];
        }

        if (empty($mediaData['front_image']) && ! empty($data['front_image'])) {
            $data['front_image_file'] = $data['front_image'];
            $data['front_image'] = Storage::disk(config('media-library.private_disk_name'))->temporaryUrl($data['front_image'], now()->addHour());
        } elseif (! empty($mediaData['front_image'])) {
            $data['front_image_file'] = $mediaData['front_image_file'];
            $data['front_image'] = $mediaData['front_image'];
        }

        foreach (['front', 'back'] as $name) {
            if (! empty($data[$name]) && ! empty($data["{$name}_image"])) {
                $data[$name] = preg_replace_callback('/<image[^>]+>/im', function ($matches) use (&$data, $name) {
                    $imageTeg = $matches[0];
                    preg_match('@href="([^"]+)"@', $imageTeg, $match);
                    $href = array_pop($match);
                    if (! empty($href)) {
                        $imageTeg = str_replace($href, $data["{$name}_image"], $imageTeg);
                    }

                    return $imageTeg;
                }, $data[$name]);
                unset($data["{$name}_image"]);
            }
        }

        unset($data['story'], $this->additional['story']);

        return $data;
    }
}
