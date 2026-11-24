<?php

return [

    'paths' => ['api/*'],

    'allowed_methods' => ['*'],

    // Origines autorisées, séparées par des virgules (Docker : http://localhost:5174).
    'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', 'http://localhost:5173')),

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => false,

];
