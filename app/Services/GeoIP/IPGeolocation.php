<?php

namespace App\Services\GeoIP;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\IPLocation;

class IPGeolocation
{
    private const MOROCCAN_TEST_IPS = [
        'marrakesh' => '105.158.107.135',
        'casablanca' => '105.158.106.23',
        'rabat' => '105.157.3.214',
        'tangier' => '196.217.12.241',
        'agadir' => '105.157.96.120'
    ];

    private const DEFAULT_LOCATION = [
        'country' => 'Morocco',
        'city' => 'Marrakesh',
        'latitude' => 31.62252,
        'longitude' => -7.98983,
        'timezone' => 'Africa/Casablanca',
        'currency' => 'MAD',
        'success' => true
    ];

    protected $client;

    public function __construct()
    {
        $this->client = Http::baseUrl('https://api.ipgeolocation.io/')
            ->withQueryParameters([
                'apiKey' => config('services.ipgeolocation.key'),
            ]);
    }

    public function locate(?string $ip = null): array
    {
        try {
            // Handle null IP or local IP
            if (!$ip || $this->isLocalIP($ip)) {
                $ip = self::MOROCCAN_TEST_IPS['marrakesh'];
                Log::info('Using test IP for local/null IP', ['ip' => $ip]);
            }

            // Check cache first
            $cachedLocation = IPLocation::where('ip', $ip)->first();
            if ($cachedLocation) {
                return $this->formatLocationResponse($cachedLocation);
            }

            // Get fresh data from API
            $response = $this->client->get('ipgeo', [
                'ip' => $ip,
                'fields' => 'geo,time_zone,currency'
            ]);

            if (!$response->successful()) {
                Log::warning('IPGeolocation request failed', [
                    'ip' => $ip,
                    'status' => $response->status(),
                    'response' => $response->json()
                ]);
                return self::DEFAULT_LOCATION;
            }

            $data = $response->json();

            // Store the data
            $location = IPLocation::create([
                'ip' => $ip,
                'continent_code' => $data['continent_code'] ?? null,
                'continent_name' => $data['continent_name'] ?? null,
                'country_code2' => $data['country_code2'] ?? null,
                'country_code3' => $data['country_code3'] ?? null,
                'country_name' => $data['country_name'] ?? null,
                'country_name_official' => $data['country_name_official'] ?? null,
                'country_capital' => $data['country_capital'] ?? null,
                'state_prov' => $data['state_prov'] ?? null,
                'state_code' => $data['state_code'] ?? null,
                'district' => $data['district'] ?? null,
                'city' => $data['city'] ?? null,
                'zipcode' => $data['zipcode'] ?? null,
                'latitude' => $data['latitude'] ?? null,
                'longitude' => $data['longitude'] ?? null,
                'is_eu' => $data['is_eu'] ?? false,
                'calling_code' => $data['calling_code'] ?? null,
                'country_tld' => $data['country_tld'] ?? null,
                'languages' => $data['languages'] ?? null,
                'country_flag' => $data['country_flag'] ?? null,
                'geoname_id' => $data['geoname_id'] ?? null,
                'isp' => $data['isp'] ?? null,
                'connection_type' => $data['connection_type'] ?? null,
                'organization' => $data['organization'] ?? null,
                'country_emoji' => $data['country_emoji'] ?? null,
                'currency_data' => $data['currency'] ?? null,
                'timezone_data' => $data['time_zone'] ?? null
            ]);

            return $this->formatLocationResponse($data);

        } catch (Exception $e) {
            Log::error('IPGeolocation Error:', [
                'ip' => $ip,
                'error' => $e->getMessage()
            ]);

            return self::DEFAULT_LOCATION;
        }
    }

    private function formatLocationResponse($data): array
    {
        if ($data instanceof IPLocation) {
            return [
                'country' => $data->country_name,
                'city' => $data->city,
                'latitude' => (float)$data->latitude,
                'longitude' => (float)$data->longitude,
                'timezone' => $data->timezone_data['name'] ?? 'Africa/Casablanca',
                'currency' => $data->currency_data['code'] ?? 'MAD',
                'success' => true
            ];
        }

        return [
            'country' => $data['country_name'] ?? 'Morocco',
            'city' => $data['city'] ?? 'Marrakesh',
            'latitude' => (float)($data['latitude'] ?? 31.62252),
            'longitude' => (float)($data['longitude'] ?? -7.98983),
            'timezone' => $data['time_zone']['name'] ?? 'Africa/Casablanca',
            'currency' => $data['currency']['code'] ?? 'MAD',
            'success' => true
        ];
    }

    private function isLocalIP(string $ip): bool
    {
        return in_array($ip, ['127.0.0.1', '::1']) || 
               preg_match('/^(192\.168\.|10\.|172\.(1[6-9]|2[0-9]|3[0-1])\.)/', $ip);
    }

    public function checkStatus(): array
    {
        try {
            $apiKey = config('services.ipgeolocation.key');
            
            if (empty($apiKey)) {
                return [
                    'working' => false,
                    'message' => 'API key not configured',
                    'api_key_valid' => false
                ];
            }

            $response = $this->client->get('ipgeo', [
                'ip' => self::MOROCCAN_TEST_IPS['marrakesh']
            ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'working' => true,
                    'message' => "API Working - Test location: {$data['city']}, {$data['country_name']}",
                    'api_key_valid' => true
                ];
            }

            return [
                'working' => false,
                'message' => $response->json()['message'] ?? 'API request failed',
                'api_key_valid' => false
            ];

        } catch (Exception $e) {
            Log::error('IPGeolocation Test Error:', [
                'error' => $e->getMessage()
            ]);

            return [
                'working' => false,
                'message' => 'Service error: ' . $e->getMessage(),
                'api_key_valid' => false
            ];
        }
    }
}