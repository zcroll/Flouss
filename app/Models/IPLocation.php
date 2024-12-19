<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IPLocation extends Model
{
    protected $table = 'ip_locations';

    protected $fillable = [
        'ip',
        'continent_code',
        'continent_name',
        'country_code2',
        'country_code3',
        'country_name',
        'country_name_official',
        'country_capital',
        'state_prov',
        'state_code',
        'district',
        'city',
        'zipcode',
        'latitude',
        'longitude',
        'is_eu',
        'calling_code',
        'country_tld',
        'languages',
        'country_flag',
        'geoname_id',
        'isp',
        'connection_type',
        'organization',
        'country_emoji',
        'currency_data',
        'timezone_data',
        'expires_at'
    ];

    protected $casts = [
        'is_eu' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
        'currency_data' => 'array',
        'timezone_data' => 'array',
        'expires_at' => 'datetime'
    ];

    protected $dates = [
        'expires_at'
    ];
} 