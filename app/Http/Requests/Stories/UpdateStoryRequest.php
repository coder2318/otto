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
            'title' => ['sometimes', 'required', 'string'],
            'cover' => ['sometimes', 'nullable', 'image'],
            'status' => ['sometimes', 'required', new Enum(Status::class)],
            'redirect' => ['sometimes', 'nullable'],
        ];
    }
}
