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
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'whatsapp' => [
        'api_url'     => env('WHATSAPP_API_URL'),
        'token'       => env('WHATSAPP_API_TOKEN'),
        'campaing_id' => env('WHATSAPP_CAMPAING_ID'),
    ],

    'pan' => [
        'pan_api_base_url'   => env('PAN_API_BASE_URL'),
        'pan_api_auth_token' => env('PAN_API_AUTH_TOKEN'),
    ],

    'gst' => [
        'gst_in_api_base_url'   => env('GST_IN_API_BASE_URL'),
        'gst_in_api_auth_token' => env('GST_IN_API_AUTH_TOKEN'),
    ],

    'whatsapp_cloud' => [
        'from_phone_number_id' => env('WHATSAPP_CLOUD_FROM_PHONE_NUMBER_ID'),
        'access_token'         => env('WHATSAPP_CLOUD_ACCESS_TOKEN'),
    ],

];
