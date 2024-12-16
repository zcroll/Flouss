<?php

return [
    /*
    |--------------------------------------------------------------------------
    | GeoIP Driver Type
    |--------------------------------------------------------------------------
    */
    'service' => 'ipgeolocation',

    /*
    |--------------------------------------------------------------------------
    | Service Settings
    |--------------------------------------------------------------------------
    */
    'services' => [
        'ipgeolocation' => [
            'class' => \App\Services\GeoIP\IPGeolocation::class,
            'key' => env('IPGEOLOCATION_KEY'),
            'lang' => ['en'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Location
    |--------------------------------------------------------------------------
    */
    'default_location' => [
        'ip' => '127.0.0.0',
        'iso_code' => 'MA',
        'country' => 'Morocco',
        'city' => 'Casablanca',
        'state' => 'CAS',
        'state_name' => 'Casablanca',
        'postal_code' => '20000',
        'lat' => 33.5731,
        'lon' => -7.5898,
        'timezone' => 'Africa/Casablanca',
        'continent' => 'AF',
        'currency' => 'MAD',
        'default' => true,
    ],

    'cache' => 'file',
    'cache_tags' => null,
    'cache_expires' => 30,
]; 