<?php

namespace App\Repositories;

use App\DTOs\LocationData;
use App\Models\IPLocation;
use App\Repositories\Contracts\IPLocationRepository;

class EloquentIPLocationRepository implements IPLocationRepository
{
    public function findByIp(string $ip): ?IPLocation
    {
        return IPLocation::where('ip', $ip)->first();
    }

    public function save(LocationData $locationData): IPLocation
    {
        return IPLocation::create($locationData->toArray());
    }
} 