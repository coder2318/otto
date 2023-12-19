<?php

namespace App\Rules;

use App\Services\TranslateService;
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
        if ($this->min < Str::wordCount(strip_tags($value))) {
            return;
        }

        $value = app(TranslateService::class)->translate(strip_tags($value), ['target' => 'en'])['text'];

        if ($this->min < Str::wordCount($value)) {
            return;
        }

        $fail('validation.min.words')?->translate([
            'attribute' => $attribute,
            'min' => $this->min,
        ]);
    }
}
