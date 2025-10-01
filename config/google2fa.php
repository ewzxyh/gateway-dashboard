<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google2FA Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration options for Google2FA.
    |
    */

    'enabled' => env('GOOGLE2FA_ENABLED', true),

    'issuer' => env('GOOGLE2FA_ISSUER', 'PlayGame'),

    'window' => env('GOOGLE2FA_WINDOW', 1),

    'algorithm' => env('GOOGLE2FA_ALGORITHM', 'sha1'),

    'digits' => env('GOOGLE2FA_DIGITS', 6),

    'period' => env('GOOGLE2FA_PERIOD', 30),

    'replay_attack_protection' => env('GOOGLE2FA_REPLAY_ATTACK_PROTECTION', true),

    'qrcode' => [
        'size' => env('GOOGLE2FA_QRCODE_SIZE', 200),
        'margin' => env('GOOGLE2FA_QRCODE_MARGIN', 4),
    ],
];
