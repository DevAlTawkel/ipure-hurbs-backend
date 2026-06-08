<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:3000',
        'http://127.0.0.1:3000',
        'https://ipureherbs.org',
        'https://www.ipureherbs.org',
    ],

    'allowed_headers' => ['*'],

    'supports_credentials' => true,

];