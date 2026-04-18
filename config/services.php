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
    'github' => [
        'client_id' => env('GITHUB_CLIENT_ID'),
        'client_secret' => env('GITHUB_CLIENT_SECRET'),
        'redirect' => env('GITHUB_CALLBACK_URL'),
    ],
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_CALLBACK_URL'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'redirect' => env('FACEBOOK_CALLBACK_URL'),
    ],
    'paymongo' => [
        'secret_key' => env('PAYMONGO_SECRET_KEY'),
        'public_key' => env('PAYMONGO_PUBLIC_KEY'),
        'webhook_secret' => env('PAYMONGO_WEBHOOK_SECRET'),
        'payment_method_types' => array_values(array_filter(array_map(
            static fn (string $value): string => trim($value),
            explode(',', (string) env('PAYMONGO_PAYMENT_METHOD_TYPES', 'card,gcash'))
        ))),
    ],

    'firebase' => [
        'api_key' => env('FIREBASE_API_KEY'),
        'auth_domain' => env('FIREBASE_AUTH_DOMAIN'),
        'project_id' => env('FIREBASE_PROJECT_ID'),
        'app_id' => env('FIREBASE_APP_ID'),
        'messaging_sender_id' => env('FIREBASE_MESSAGING_SENDER_ID'),
        'app_check_site_key' => env('FIREBASE_APP_CHECK_SITE_KEY'),
        'app_check_debug_token' => env('FIREBASE_APP_CHECK_DEBUG_TOKEN'),
        'enable_app_check' => filter_var(env('FIREBASE_ENABLE_APP_CHECK', false), FILTER_VALIDATE_BOOL),
    ],

    'promo' => [
        'daily_code' => env('PROMO_DAILY_CODE', 'LUMIPRO'),
        'daily_discount_percent' => (float) env('PROMO_DAILY_DISCOUNT_PERCENT', 5),
    ],

];
