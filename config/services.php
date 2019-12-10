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

    'currency_converter' => [
        'base_uri' => env('CURRENCY_CONVERTER_BASE_URI'),
        'api_key' => env('CURRENCY_CONVERTER_KEY'),
    ],

    'epayco' => [
        'class' => App\Services\EPaycoService::class,
        'base_uri' => env('EPAYCO_BASE_URI'),
        'public_key' => env('EPAYCO_PUBLIC_KEY'),
        'private_key' => env('EPAYCO_PRIVATE_KEY'),
        'base_currency' => 'cop'
    ],

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'mercadopago' => [
        'class' => App\Services\MercadoPagoService::class,
        'base_uri' => env('MERCADOPAGO_BASE_URI'),
        'public_key' => env('MERCADOPAGO_PUBLIC_KEY'),
        'access_token' => env('MERCADOPAGO_ACCESS_TOKEN'),
        'base_currency' => 'cop'
    ],

    'paypal' => [
        'class' => App\Services\PayPalService::class,
        'base_uri' => env('PAYPAL_BASE_URI'),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
    ],

    'payu' => [
        'class' => App\Services\PayUService::class,
        'base_uri' => env('PAYU_BASE_URI'),
        'api_login' => env('PAYU_API_LOGIN'),
        'api_key' => env('PAYU_API_KEY'),
        'public_key' => env('PAYU_PUBLIC_KEY'),
        'merchant_id' => env('PAYU_MERCHANT_ID'),
        'base_currency' => 'cop'
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
        'class' => App\Services\StripeService::class,
        'base_uri' => env('STRIPE_BASE_URI'),
        'public_key' => env('STRIPE_PUBLIC_KEY'),
        'secret_key' => env('STRIPE_SECRET_KEY'),
    ],

];
