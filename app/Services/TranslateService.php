<?php

namespace App\Services;

use Google\Cloud\Translate\V2\TranslateClient;

class TranslateService
{
    public function translate(string $text, string $target): string
    {
        if (config('services.google.translate.fake', true)) {
            return $text;
        }

        $result = app(TranslateClient::class)->translate($text, compact('target'));

        if (isset($result['text'])) {
            return $result['text'];
        }
    }
}
