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
     * @param  \Closure(string): ?\Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if ($this->min < $count = Str::wordCount(strip_tags($value))) {
            return;
        }

        // Crutch to validate languages without spaces (Hindi, Chinese, Japanese etc.)
        if ($count <= $this->min && $this->min < Str::length(strip_tags($value))) {
            return;
        }

        $fail('validation.min.words')?->translate([
            'attribute' => $attribute,
            'min' => $this->min,
        ]);
    }
}
