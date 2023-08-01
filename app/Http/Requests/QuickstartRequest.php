<?php

namespace App\Http\Requests;

use App\Data\User\Details;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class QuickstartRequest extends FormRequest
{
    public function prepareForValidation(): void
    {
        $this->merge([
            'birth_date' => ($date = Carbon::createFromFormat('d/m/Y', $this->birth_date)) ?
                $date->startOfDay() : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return Details::getValidationRules($this->all());
    }

    public function details(?Details $details): Details
    {
        if (! $details) {
            $details = new Details();
        }

        foreach ($this->validated() as $key => $value) {
            $details->$key = $value;
        }

        return $details;
    }
}
