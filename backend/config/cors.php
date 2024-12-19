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

    'paths' => ['*'], // Permitir todas as rotas (inclui rotas web)

    'allowed_methods' => ['*'], // Permite todos os métodos HTTP (GET, POST, etc.)

    'allowed_origins' => ['http://localhost:5173'], // Permite apenas o Vue em desenvolvimento

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'], // Permite todos os cabeçalhos (requisitados pelo Axios, por exemplo)

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Permite envio de cookies e CSRF
];
