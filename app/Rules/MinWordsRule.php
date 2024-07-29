<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

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
        $value = strip_tags($value);
        if ($this->min < self::wordsCount($value)) {
            return;
        }

        $fail('validation.min.words')?->translate([
            'attribute' => $attribute,
            'min' => $this->min,
        ]);
    }

    //https://github.com/byn9826/words-count/blob/master/src/globalWordsCount.js
    const DEFAULT_PUNCTUATION = [
        ',', '，', '.', '。', ':', '：', ';', '；', '[', ']', '【', ']', '】', '{', '｛', '}', '｝',
        '(', '（', ')', '）', '<', '《', '>', '》', '$', '￥', '!', '！', '?', '？', '~', '～',
        "'", '’', '"', '“', '”',
        '*', '/', '\\', '&', '%', '@', '#', '^', '、', '、', '、', '、',
    ];

    const EMPTY_RESULT = [
        'words' => [],
        'count' => 0,
    ];

    public static function wordsDetect($text, $config = [])
    {
        if (! $text) {
            return self::EMPTY_RESULT;
        }
        $words = (string) $text;
        if (trim($words) === '') {
            return self::EMPTY_RESULT;
        }
        $punctuationReplacer = isset($config['punctuationAsBreaker']) && $config['punctuationAsBreaker'] ? ' ' : '';
        $defaultPunctuations = isset($config['disableDefaultPunctuation']) && $config['disableDefaultPunctuation'] ? [] : self::DEFAULT_PUNCTUATION;
        $customizedPunctuations = isset($config['punctuation']) ? $config['punctuation'] : [];
        $combinedPunctuations = array_merge($defaultPunctuations, $customizedPunctuations);

        // Remove punctuations or change to empty space
        foreach ($combinedPunctuations as $punctuation) {
            $punctuationReg = '/'.preg_quote($punctuation, '/').'/u';
            $words = preg_replace($punctuationReg, $punctuationReplacer, $words);
        }

        // Remove all kind of symbols
        $words = preg_replace('/[\x{FF00}-\x{FFEF}\x{2000}-\x{206F}]/u', '', $words);
        // Format white space character
        $words = preg_replace('/\s+/', ' ', $words);
        // Split words by white space (For European languages)
        $words = explode(' ', $words);
        $words = array_filter($words, function ($word) {
            return trim($word);
        });

        // Match latin, cyrillic, Malayalam letters and numbers
        $common = '(\d+)|[a-zA-Z}\x{00C0}\x{00FF}\x{0100}\x{017F}\x{0180}\x{024F}\x{0250}\x{02AF}\x{1E00}\x{1EFF}\x{0400}\x{04FF}\x{0500}\x{052F}\x{0D00}\x{0D7F}]+|';
        // Match Chinese Hànzì, the Japanese Kanji and the Korean Hanja
        $cjk = '\x{2E80}\x{2EFF}\x{2F00}\x{2FDF}\x{3000}\x{303F}\x{31C0}\x{31EF}\x{3200}\x{32FF}\x{3300}\x{33FF}\x{3400}\x{3FFF}\x{4000}\x{4DBF}\x{4E00}\x{4FFF}\x{5000}\x{5FFF}\x{6000}\x{6FFF}\x{7000}\x{7FFF}\x{8000}\x{8FFF}\x{9000}\x{9FFF}\x{F900}\x{FAFF}';
        // Match Japanese Hiragana, Katakana, Rōmaji
        $jp = '\x{3040}\x{309F}\x{30A0}\x{30FF}\x{31F0}\x{31FF}\x{3190}\x{319F}';
        // Match Korean Hangul
        $kr = '\x{1100}\x{11FF}\x{3130}\x{318F}\x{A960}\x{A97F}\x{AC00}\x{AFFF}\x{B000}\x{BFFF}\x{C000}\x{CFFF}\x{D000}\x{D7AF}\x{D7B0}\x{D7FF}';

        $reg = '/'.$common.'['.$cjk.$jp.$kr.']/u';

        $detectedWords = [];

        foreach ($words as $word) {
            $matches = [];
            preg_match_all($reg, $word, $matches);
            if (empty($matches[0])) {
                $detectedWords[] = $word;
            } else {
                $detectedWords = array_merge($detectedWords, $matches[0]);
            }
        }

        return [
            'words' => $detectedWords,
            'count' => count($detectedWords),
        ];
    }

    public static function wordsCount($text, $config = [])
    {
        $result = self::wordsDetect($text, $config);

        return $result['count'];
    }

    public static function wordsSplit($text, $config = [])
    {
        $result = self::wordsDetect($text, $config);

        return $result['words'];
    }
}
