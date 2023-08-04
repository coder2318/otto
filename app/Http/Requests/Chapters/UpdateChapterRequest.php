<?php

namespace App\Http\Requests\Chapters;

use App\Data\Chapter\Status;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateChapterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['sometimes', 'required', 'string'],
            'content' => ['sometimes', 'required', 'string'],
            'status' => ['required', new Enum(Status::class)],
        ];
    }
}
