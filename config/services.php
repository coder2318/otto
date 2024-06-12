<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'plans' => env('STRIPE_PLANS') ? explode(',', env('STRIPE_PLANS')) : [],
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT'),
        'analytics_tag' => env('GOOGLE_ANALYTICS_TAG'),

        'translate' => [
            'fake' => env('GOOGLE_TRANSLATE_FAKE', env('APP_ENV', 'production') !== 'production'),
            'key' => env('GOOGLE_TRANSLATE_KEY'),
        ],
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_REDIRECT'),
    ],

    'deepgram' => [
        'key' => env('DEEPGRAM_API_KEY'),
        'fake' => env('DEEPGRAM_FAKE', env('APP_ENV', 'production') !== 'production'),
    ],

    'anthropic' => [
        'key' => env('ANTHROPIC_API_KEY'),
    ],

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'fake' => env('OPENAI_FAKE', env('APP_ENV', 'production') !== 'production'),
        'models' => [
            'audio' => 'whisper-1',
            'chat' => 'gpt-4-1106-preview',
            'completions' => 'text-davinci-003',
            'edits' => 'text-davinci-edit-001',
        ],
    ],

    'lulu' => [
        'key' => env('LULU_API_KEY'),
        'secret' => env('LULU_SECRET'),
        'url' => env('LULU_API'),
        'encoded_key' => env('LULU_ENCODED'),
        /**
         * 0600X0900: trim size 6” x 9”
         * FC: full color
         * STD: standard quality
         * PB: perfect binding
         * 080CW444: 80# coated white paper with a bulk of 444 ppi
         * G: gloss cover coating
         * X: no linen
         * X: no foil
         */
        'product' => '0600X0900FCSTDPB080CW444GXX',
    ],

    'sqids' => [
        'dictionary' => env('SQIDS_DICTIONARY', \Sqids\Sqids::DEFAULT_ALPHABET),
    ],

    'admin' => [
        'email' => env('ADMIN_EMAIL'),
    ],

];
