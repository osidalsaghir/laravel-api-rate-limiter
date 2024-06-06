<?php

return [
    'global' => [
        'limit' => 1000, // Requests per hour
        'burst' => 50, // Burst limit
    ],
    'routes' => [
        // example of route-specific limits
        // 'api/v1/users' => [
        //     'limit' => 500,
        //     'burst' => 20,
        // ],
        // 'api/v1/orders' => [
        //     'limit' => 200,
        //     'burst' => 10,
        // ],
    ],
    'user_based' => true, // Enable user-specific limits
    'ip_based' => true, // Enable IP-based limits
    'cache_driver' => 'redis', // Cache driver to use
    'response_messages' => [
        'rate_limit_exceeded' => 'Too many requests. Please try again later.',
    ],
];
