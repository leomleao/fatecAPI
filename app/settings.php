<?php

return [
    'settings' => [
        // Slim Settings
        'determineRouteBeforeAppMiddleware' => false,
        'displayErrorDetails' => true,
        'PoweredBy' => 'fatecTCC',

        // database settings
        'pdo' => [
            'dsn' => 'mysql:host=mysql.hostinger.com.br;dbname=u665071295_main;charset=utf8',
            'username' => 'u665071295_root',
            'password' => 'Stronghold',
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
