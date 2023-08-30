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
            'content' => ['sometimes', 'nullable', 'string'],
            'timeline_id' => ['sometimes', 'required', 'integer', 'exists:timelines,id'],
            'cover' => ['sometimes', 'required', 'image', 'max:2048'],
            'attachments' => ['sometimes', 'nullable', 'array'],
            'attachments.*' => ['required', 'file', 'mimes:webm,weba,wav,mp3,text,txt,pdf,docx', 'max:10240'],
            'status' => ['sometimes', 'required', new Enum(Status::class)],
            'redirect' => ['sometimes', 'nullable'],
        ];
    }
}
