<?php

return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
        'PoweredBy' => 'Grupo TCC',

        // database settings
        'pdo' => [
            'dsn' => 'mysql:host=localhost;dbname=fatecdb11;charset=utf8',
            'username' => 'root',
            'password' => 'root',
        ],
        
        // api rate limiter settings
        'api_rate_limiter' => [
            'requests' => '200',
            'inmins' => '60',
        ],

        // monolog settings
        'logger' => [
            'name' => 'app',
            'path' => __DIR__.'/../log/app.log',
        ],
    ],
];
