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

    'fe' => [
            'url' => env('APP_FE_URL', 'http://localhost:3000'),
            'url_user' => env('APP_FE_URL_USER', 'http://localhost:3000'),
            'url_home' => env('APP_REDIRECT_URI_HOME','/home'),
            'url_account' => env('APP_REDIRECT_URL_WEB','/terms'),
            'url_payment' => env('APP_FE_URL_PAYMENT','/validation'),
    ],

];
