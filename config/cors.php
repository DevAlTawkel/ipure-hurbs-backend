<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3001',
        'http://localhost:3000',
        'http://localhost:5173',
        'http://127.0.0.1:3000',
        'https://ipureherbs.org',
        'https://www.ipureherbs.org',
        'https://api.ipureherbs.org',
        'https://staging.ipureherbs.org',
    ],

    'allowed_origins_patterns' => [
        '#^https://.*\.ipure-hurbs-frontend\.pages\.dev$#',
    ],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];