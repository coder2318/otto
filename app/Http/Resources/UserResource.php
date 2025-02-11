<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\User */
class UserResource extends JsonResource
{
    /**
     * Indicates if the resource's collection keys should be preserved.
     */
    public bool $preserveKeys = true;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return array_merge(parent::toArray($request), [
            'subscribed' => $this->subscribed(),
            'avatar' => $this->whenLoaded('avatar', fn () => $this->resource->avatar->getUrl()),
            'unreadNotifications' => $this->whenLoaded('unreadNotifications', fn () => $this->resource->unreadNotifications),
        ]);
    }
}
