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
            'title' => ['sometimes', 'required', 'string', 'max:255', 'min:3'],
            'saveAsNewUserTemplate' => ['sometimes', 'nullable', 'boolean'],
            'cover' => ['sometimes', 'nullable'],
            'meta' => ['sometimes', 'nullable', 'array'],
            'book_cover' => ['sometimes', 'nullable', 'image'],
            'status' => ['sometimes', 'required', new Enum(Status::class)],
            'redirect' => ['sometimes', 'nullable'],
        ];
    }
}
