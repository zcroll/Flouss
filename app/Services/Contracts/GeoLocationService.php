<?php

namespace App\Services\Contracts;

interface GeoLocationService
{
    public function getLocation(?string $ip = null): array;
    public function checkStatus(): array;
} 