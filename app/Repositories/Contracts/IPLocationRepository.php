<?php

namespace App\Repositories\Contracts;

use App\DTOs\LocationData;
use App\Models\IPLocation;

interface IPLocationRepository
{
    public function findByIp(string $ip): ?IPLocation;
    public function save(LocationData $locationData): IPLocation;
} 