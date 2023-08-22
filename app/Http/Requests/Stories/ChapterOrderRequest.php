<?php

namespace App\Http\Requests\Stories;

use Illuminate\Foundation\Http\FormRequest;

class ChapterOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'timelines' => ['nullable', 'array'],
            'timelines.*' => ['required', 'array'],
            'timelines.*.id' => ['required', 'integer', 'exists:timelines,id'],
            'timelines.*.chapters' => ['nullable', 'array', 'exists:chapters,id'],
            'timelines.*.chapters.*' => ['required', 'integer'],
            'redirect' => ['sometimes', 'nullable'],
        ];
    }
}
