<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Intervention\Image\Facades\Image;

class GuestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'avatar' => $this->whenLoaded('avatar', fn() => $this->resource->avatar->getUrl()),
        ]);
    }

    public function processAvatar()
    {
        if ($this->resource->avatar) {
            $this->processed_avatar = $this->processAvatarImage($this->resource->avatar->getUrl());
        }

        return $this;
    }

    private function processAvatarImage(string $path): string
    {
        $image = Image::make($path);

        $size = 300;

        $image->fit($size, $size);

        $mask = Image::canvas($size, $size);
        $mask->circle($size, $size / 2, $size / 2, function ($draw) {
            $draw->background('#fff');
        });
        
        $image->mask($mask, true);

        $base64 = (string) $image->encode('data-url');

        return $base64;
    }
}
