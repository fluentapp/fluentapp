<?php

// Phpunit test environment

return function (array $settings): array {
    $settings['error']['display_error_details'] = true;
    $settings['error']['log_errors'] = true;

    // Database
    $settings['db']['database'] = 'test';

    // Mocked Logger settings
    $settings['logger'] = [
        'test' => new \Monolog\Logger('test', [
            new \Monolog\Handler\TestHandler(),
//             new \Monolog\Handler\StreamHandler('php://output', \Monolog\Level::Warning),
        ]),
    ];

    // API credentials for phpunit
    $settings['api_auth'] = [
        // Allow http for testing
        'secure' => false,
        'users' => [
            'api-user' => 'secret',
        ],
    ];

    return $settings;
};
