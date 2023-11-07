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
            'edit' => ['sometimes', 'nullable', 'string'],
            'timeline_id' => ['sometimes', 'required', 'integer', 'exists:timelines,id'],
            'cover' => ['sometimes', 'required', 'image', 'max:10240'],
            'attachments' => ['sometimes', 'nullable', 'array'],
            'attachments.*.file' => ['required', 'file', 'mimes:webm,weba,wav,mp3,text,txt,pdf,docx,m4a,m2a', 'max:51200'],
            'attachments.*.options' => ['nullable', 'array'],
            'attachments.*.translate' => ['sometimes', 'array'],
            'attachments.*.translate.source' => ['nullable', 'string'],
            'attachments.*.translate.target' => ['nullable', 'string'],
            'images' => ['sometimes', 'nullable', 'array'],
            'images.*.file' => ['required', 'file', 'image', 'max:10240'],
            'images.*.caption' => ['nullable', 'string'],
            'status' => ['sometimes', 'required', new Enum(Status::class)],
            'redirect' => ['sometimes', 'nullable'],
        ];
    }
}
