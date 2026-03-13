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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | QR Code API Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for the QR code signed URL generation API.
    |
    */
    'qr_api' => [
        'base_url' => env('QR_API_BASE_URL', 'http://localhost:8000'),
        'timeout' => env('QR_API_TIMEOUT', 10),
        'retry_times' => env('QR_API_RETRY_TIMES', 3),
        'retry_delay' => env('QR_API_RETRY_DELAY', 100),
        'secret' => env('MUNI_API_KEY'),
    ],

    'libreoffice' => [
        'bin' => env('LIBREOFFICE_BIN', '/usr/bin/soffice'),
    ],
];
