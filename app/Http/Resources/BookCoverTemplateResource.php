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

        if (! empty($data['back_image'])) {
            $data['back_image_file'] = $data['back_image'];
            $data['back_image'] = Storage::disk(config('media-library.private_disk_name'))->temporaryUrl($data['back_image'], now()->addHour());
        }

        if (! empty($data['front_image'])) {
            $data['front_image_file'] = $data['front_image'];
            $data['front_image'] = Storage::disk(config('media-library.private_disk_name'))->temporaryUrl($data['front_image'], now()->addHour());
        }

        return $data;
    }
}
