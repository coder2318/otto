<?php

namespace App\Services;

use Google\Cloud\Translate\V2\TranslateClient;

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
            return compact('text');
        }

        return $this->client->translate($text, $options);
    }

    public function __call($name, $arguments)
    {
        return $this->client->$name(...$arguments);
    }
}
