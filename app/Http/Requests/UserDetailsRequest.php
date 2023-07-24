<?php

namespace App\Http\Requests;

use App\Data\User\Details;
use Illuminate\Foundation\Http\FormRequest;

class UserDetailsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return Details::getValidationRules($this->all());
    }

    public function details(Details $details): Details
    {
        $details = Details::from([
            'first_name' => $this->validated('first_name', $details->first_name),
            'last_name' => $this->validated('last_name', $details->last_name),
            'birth_date' => $this->validated('birth_date', $details->birth_date),
            'motivation' => $this->validated('motivation', $details->motivation),
            'writing_style' => $this->validated('writing_style', $details->writing_style),
            'goals' => $this->validated('goals', $details->goals),
            'timeline' => $this->validated('timeline', $details->timeline),
        ]);

        $details->configured = $details->first_name &&
            $details->last_name &&
            $details->birth_date &&
            $details->motivation &&
            $details->writing_style &&
            $details->goals &&
            $details->timeline;

        return $details;
    }
}
