<?php

namespace App\DTOs;

use Illuminate\Contracts\Support\Arrayable;

class LocationData implements Arrayable
{
    public function __construct(
        public readonly string $ip,
        public readonly ?string $continentCode,
        public readonly ?string $continentName,
        public readonly ?string $countryCode2,
        public readonly ?string $countryCode3,
        public readonly ?string $countryName,
        public readonly ?string $countryNameOfficial,
        public readonly ?string $countryCapital,
        public readonly ?string $stateProv,
        public readonly ?string $stateCode,
        public readonly ?string $district,
        public readonly ?string $city,
        public readonly ?string $zipcode,
        public readonly ?float $latitude,
        public readonly ?float $longitude,
        public readonly bool $isEu,
        public readonly ?string $callingCode,
        public readonly ?string $countryTld,
        public readonly ?string $languages,
        public readonly ?string $countryFlag,
        public readonly ?string $geonameId,
        public readonly ?string $isp,
        public readonly ?string $connectionType,
        public readonly ?string $organization,
        public readonly ?string $countryEmoji,
        public readonly array $currencyData,
        public readonly array $timezoneData,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            ip: $data['ip'],
            continentCode: $data['continent_code'] ?? null,
            continentName: $data['continent_name'] ?? null,
            countryCode2: $data['country_code2'] ?? null,
            countryCode3: $data['country_code3'] ?? null,
            countryName: $data['country_name'] ?? null,
            countryNameOfficial: $data['country_name_official'] ?? null,
            countryCapital: $data['country_capital'] ?? null,
            stateProv: $data['state_prov'] ?? null,
            stateCode: $data['state_code'] ?? null,
            district: $data['district'] ?? null,
            city: $data['city'] ?? null,
            zipcode: $data['zipcode'] ?? null,
            latitude: $data['latitude'] ? (float) $data['latitude'] : null,
            longitude: $data['longitude'] ? (float) $data['longitude'] : null,
            isEu: (bool) ($data['is_eu'] ?? false),
            callingCode: $data['calling_code'] ?? null,
            countryTld: $data['country_tld'] ?? null,
            languages: is_array($data['languages'] ?? null) ? implode(',', $data['languages']) : $data['languages'],
            countryFlag: $data['country_flag'] ?? null,
            geonameId: $data['geoname_id'] ?? null,
            isp: $data['isp'] ?? null,
            connectionType: $data['connection_type'] ?? null,
            organization: $data['organization'] ?? null,
            countryEmoji: $data['country_emoji'] ?? null,
            currencyData: is_array($data['currency'] ?? null) ? $data['currency'] : ['code' => null, 'name' => null, 'symbol' => null],
            timezoneData: is_array($data['time_zone'] ?? null) ? $data['time_zone'] : ['name' => null, 'offset' => null]
        );
    }

    public function toArray(): array
    {
        return [
            'ip' => $this->ip,
            'continent_code' => $this->continentCode,
            'continent_name' => $this->continentName,
            'country_code2' => $this->countryCode2,
            'country_code3' => $this->countryCode3,
            'country_name' => $this->countryName,
            'country_name_official' => $this->countryNameOfficial,
            'country_capital' => $this->countryCapital,
            'state_prov' => $this->stateProv,
            'state_code' => $this->stateCode,
            'district' => $this->district,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_eu' => $this->isEu,
            'calling_code' => $this->callingCode,
            'country_tld' => $this->countryTld,
            'languages' => $this->languages,
            'country_flag' => $this->countryFlag,
            'geoname_id' => $this->geonameId,
            'isp' => $this->isp,
            'connection_type' => $this->connectionType,
            'organization' => $this->organization,
            'country_emoji' => $this->countryEmoji,
            'currency_data' => $this->currencyData,
            'timezone_data' => $this->timezoneData
        ];
    }

    public function toMonitoringArray(): array
    {
        return [
            'country' => $this->countryName ?? 'Morocco',
            'city' => $this->city ?? 'Marrakesh',
            'latitude' => $this->latitude ?? 31.62252,
            'longitude' => $this->longitude ?? -7.98983,
            'timezone' => $this->timezoneData['name'] ?? 'Africa/Casablanca',
            'currency' => $this->currencyData['code'] ?? 'MAD',
            'success' => true
        ];
    }
} 