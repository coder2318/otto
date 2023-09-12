<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class MinWordsRule implements ValidationRule
{
    public function __construct(
        protected int $min
    ) {
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (Str::wordCount(strip_tags($value)) < $this->min) {
            $fail('validation.min.words')->translate([
                'attribute' => $attribute,
                'min' => $this->min,
            ]);
        }
    }
}
