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

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'google_maps' => [
        'key' => env('GOOGLE_MAPS_API_KEY'),
    ],

    'kvk' => [
        'base_url' => env('KVK_API_BASE_URL', env('KVK_API_BASE', 'https://api.kvk.nl')),
        'key' => env('KVK_API_KEY', env('KVK_KEY')),
        'key_header' => env('KVK_API_KEY_HEADER', 'apikey'),
        'timeout' => (int) env('KVK_API_TIMEOUT', 10),
    ],

    'overpass' => [
        'base_url' => env('OVERPASS_API_BASE_URL', 'https://overpass-api.de/api/interpreter'),
        'timeout' => (int) env('OVERPASS_API_TIMEOUT', 120),
    ],

];
