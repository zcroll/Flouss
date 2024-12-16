<?php

return [
    'enabled' => env('MONITORING_ENABLED', true),
    
    'excluded_paths' => [
        '_debugbar/*',
        'admin/*',
        'horizon/*',
        'nova/*',
    ],
    
    'use_reverse_proxy_ip' => env('MONITORING_USE_PROXY_IP', false),
    'real_ip_header' => env('MONITORING_IP_HEADER', 'X-Forwarded-For'),
    
    'retention_days' => env('MONITORING_RETENTION_DAYS', 30),
    
    'pruning' => [
        'enabled' => true,
        'schedule' => '0 0 * * *', // Daily at midnight
    ],
    
    'authentication' => [
        'enabled' => env('MONITORING_AUTH_ENABLED', true),
        'track_failed_attempts' => true,
        'track_location' => false, // Requires additional setup for IP geolocation
    ],
]; 