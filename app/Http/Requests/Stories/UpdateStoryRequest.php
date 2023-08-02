<?php

namespace App\Http\Requests\Stories;

use App\Data\Story\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateStoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'cover' => ['nullable', 'image'],
            'status' => ['required', new Enum(Status::class)],
        ];
    }
}
