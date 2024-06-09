<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Server
    |--------------------------------------------------------------------------
    |
    |
    */

    'horizon' => [
        'type' => env('HORIZON_TYPE', 'testnet'),
        'host' => env('HORIZON_HOST', 'https://horizon-testnet.stellar.org')
    ],

    /*
    |--------------------------------------------------------------------------
    | Signing Key
    |--------------------------------------------------------------------------
    | The server's signing key
    |
    */

    'signing_key' => [
        'public' => env('SIGNING_PUBLIC_KEY'),
        'secret' => env('SIGNING_SECRET_KEY')
    ],

    /*
    |--------------------------------------------------------------------------
    | Redeemable Assets
    |--------------------------------------------------------------------------
    | This asset is redeemable for application credit
    |
    */

    'asset' => [
        'code' => env('ASSET_CODE'),
        'issuer' => env('ASSET_ISSUER')
    ],

    /*
    |--------------------------------------------------------------------------
    | Test Accounts
    |--------------------------------------------------------------------------
    |
    |
    */

    'accounts' => [
        'ASTRIFY' => [
            'public' => env('ASTRIFY_PUBLIC_KEY'),
            'secret' => env('ASTRIFY_SECRET_KEY')
        ]
    ],
];
