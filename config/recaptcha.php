<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Google reCAPTCHA Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your Google reCAPTCHA v3 settings here.
    | Get your keys from: https://www.google.com/recaptcha/admin
    |
    */

    'site_key' => env('RECAPTCHA_SITE_KEY', ''),
    'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
    
    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Score Threshold
    |--------------------------------------------------------------------------
    |
    | The minimum score required to pass verification (0.0 - 1.0)
    | 1.0 is very likely a good interaction, 0.0 is very likely a bot
    | Recommended: 0.5 for balanced protection
    |
    */
    
    'threshold' => env('RECAPTCHA_THRESHOLD', 0.5),
    
    /*
    |--------------------------------------------------------------------------
    | Enable/Disable reCAPTCHA
    |--------------------------------------------------------------------------
    |
    | Set to false to disable reCAPTCHA (useful for testing)
    |
    */
    
    'enabled' => env('RECAPTCHA_ENABLED', true),

];
