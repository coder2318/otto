<?php

namespace App\Http\Requests\Users;

use App\Data\User\Details;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class UpdateUserRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        $this->merge([
            'details' => [
                'birth_date' => ($date = Carbon::createFromFormat('d/m/Y', $this->input('details.birth_date'))) ?
                    $date->startOfDay() : null,
            ] + $this->input('details'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'name' => ['sometimes', 'required', 'string', 'max:255', 'unique:users,name,'.Auth::id()],
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,'.Auth::id()],
            'details' => ['sometimes', 'required', 'array'],
            'avatar' => ['sometimes', 'nullable', 'file', 'mimetypes:image/*', 'max:2048'],
        ];

        foreach (Details::getValidationRules($this->input('details')) as $field => $value) {
            $rules["details.$field"] = $value;
        }

        return $rules;
    }
}
