<?php

namespace App\Services;

use Google\Cloud\Translate\V2\TranslateClient;
use Statickidz\GoogleTranslate;

/**
 * @mixin TranslateClient
 */
class TranslateService
{
    public function __construct(
        protected TranslateClient $client,
    ) {
    }

    public function translate(string $text, array $options = []): ?array
    {
        if (config('services.google.translate.fake', true)) {
            return rescue(fn () => $this->translateFree($options['target'], $text), compact('text'));
        }

        return $this->client->translate($text, $options);
    }

    public function detectLanguage(string $text, array $options = []): ?array
    {
        if (config('services.google.translate.fake', true)) {
            return ['languageCode' => 'en'];
        }

        return $this->client->detectLanguage($text, $options);
    }

    public function translateFree($target, $text): array
    {
        $service = new GoogleTranslate();

        return ['text' => $service->translate('auto', $target, $text)];
    }

    public function __call($name, $arguments)
    {
        return $this->client->$name(...$arguments);
    }
}
