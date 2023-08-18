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

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID', '827681195440577'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', 'b4edbaadce48ae4ced6e82ae78c21419'),
        'redirect' => env('FACEBOOK_REDIRECT_URI', 'http://localhost:8000/auth/facebook/callback')
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID', 'ckdZSWNMd201QXNuNGdVbHlZQzU6MTpjaQ'),
        'client_secret' => env('TWITTER_CLIENT_SECRET', 'xaAORm71EUpJQrDyckRS65KsOJSs2gUjqT90DEo5ufw6zwm0AU'),
        'redirect' => env('TWITTER_REDIRECT_URI', 'http://localhost:8000/auth/twitter/callback')
    ],

    'twitter-oauth-2' => [
        'client_id' => env('TWITTER_CLIENT_ID', 'ckdZSWNMd201QXNuNGdVbHlZQzU6MTpjaQ'),
        'client_secret' => env('TWITTER_CLIENT_SECRET', 'xaAORm71EUpJQrDyckRS65KsOJSs2gUjqT90DEo5ufw6zwm0AU'),
        'redirect' => env('TWITTER_REDIRECT_URI', 'http://localhost:8000/auth/twitter/callback')
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', '78369735902-sipv7ufj0alq7jqn41vf2oqmte7frqp8.apps.googleusercontent.com'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', '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'),
        'redirect' => env('GOOGLE_REDIRECT_URI', 'http://localhost:8000/auth/google/callback')
    ],

];
