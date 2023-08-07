<?php

namespace App\Http\Requests\Chapters;

use Illuminate\Foundation\Http\FormRequest;

class TranscribeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'recordings' => ['required', 'array'],
            'recordings.*' => ['required', 'integer', 'exists:media,id'],
        ];
    }
}
