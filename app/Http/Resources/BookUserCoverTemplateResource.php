<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BookUserCoverTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $template = $this->template->toArray();

        $mediaData = $this->media->mapWithKeys(function ($media) {
            return [
                "{$media->collection_name}_image_file" => $media->getPath(),
                "{$media->collection_name}_image" => $media->getUrl(),
            ];
        })->all() ?? [];

        if (empty($mediaData['back_image']) && ! empty($template['back_image'])) {
            $template['back_image_file'] = $template['back_image'];
            $template['back_image'] = Storage::disk(config('media-library.private_disk_name'))->temporaryUrl($template['back_image'], now()->addHour());
        } elseif (! empty($mediaData['back_image'])) {
            $template['back_image_file'] = $mediaData['back_image_file'];
            $template['back_image'] = $mediaData['back_image'];
        }

        if (empty($mediaData['front_image']) && ! empty($template['front_image'])) {
            $template['front_image_file'] = $template['front_image'];
            $template['front_image'] = Storage::disk(config('media-library.private_disk_name'))->temporaryUrl($template['front_image'], now()->addHour());
        } elseif (! empty($mediaData['front_image'])) {
            $template['front_image_file'] = $mediaData['front_image_file'];
            $template['front_image'] = $mediaData['front_image'];
        }

        foreach (['front', 'back'] as $name) {
            if (! empty($template[$name]) && ! empty($template["{$name}_image"])) {
                $template[$name] = preg_replace_callback('/<image[^>]+>/im', function ($matches) use (&$template, $name) {
                    $imageTeg = $matches[0];
                    preg_match('@href="([^"]+)"@', $imageTeg, $match);
                    $href = array_pop($match);

                    if (! empty($href)) {
                        $imageTeg = str_replace($href, $template["{$name}_image"], $imageTeg);
                    }

                    return $imageTeg;
                }, $template[$name]);

                unset($template["{$name}_image"]);
            }
        }

        $data = array_merge(parent::toArray($request), [
            'id' => $this->id,
            'template_id' => $this->template_id ?? 1,
            'parameters' => array_merge($this->parameters ?? [], ['user_template_id' => $this->id]),
            'template' => $template,
        ]);

        unset($data["media"]);

        return $data;
    }
}
