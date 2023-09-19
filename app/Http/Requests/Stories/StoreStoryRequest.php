<?php

namespace App\Http\Requests\Stories;

use Illuminate\Foundation\Http\FormRequest;

class StoreStoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'cover' => ['required', 'image', 'max:4096'],
            'story_type_id' => ['required', 'integer', 'exists:story_types,id'],
        ];
    }
}
