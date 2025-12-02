<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        // Your specific GitHub Pages domain
        'https://clopes1997.github.io',
        // Or use '*' to allow all origins (not recommended for production)
        env('CORS_ALLOWED_ORIGINS', 'https://clopes1997.github.io'),
    ],

    'allowed_origins_patterns' => [
        // Pattern for GitHub Pages: *.github.io (allows any GitHub Pages subdomain)
        'https://*.github.io',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];

