<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

readonly class GeoIPService
{
    // Moroccan Test IPs with verified locations
    private const MOROCCAN_TEST_IPS = [
        'marrakesh' => [
            '105.158.107.135',  // Marrakesh, verified working
            '105.158.107.136',  // Marrakesh area
            '105.158.107.137'   // Marrakesh area
        ],
        'casablanca' => [
            '105.158.106.23',   // Casablanca region
            '105.158.106.24',   // Casablanca center
            '105.158.106.25'    // Casablanca port area
        ],
        'rabat' => [
            '105.157.3.214',    // Rabat region
            '105.157.3.215',    // Rabat center
            '105.157.3.216'     // Rabat coast
        ],
        'tangier' => [
            '196.217.12.241',   // Tangier region
            '196.217.12.242',   // Tangier port
            '196.217.12.243'    // Tangier medina
        ],
        'agadir' => [
            '105.157.96.120',   // Agadir region
            '105.157.96.121',   // Agadir beach
            '105.157.96.122'    // Agadir center
        ],
        'fes' => [
            '105.157.200.100',  // Fes region
            '105.157.200.101'   // Fes medina
        ]
    ];

    private function storeLocationData(array $data): array
    {
        return \App\Models\IPLocation::updateOrCreate(
            ['ip' => $data['ip']],
            [
                'continent_code' => $data['continent_code'],
                'continent_name' => $data['continent_name'],
                'country_code2' => $data['country_code2'],
                'country_code3' => $data['country_code3'],
                'country_name' => $data['country_name'],
                'country_name_official' => $data['country_name_official'],
                'country_capital' => $data['country_capital'],
                'state_prov' => $data['state_prov'],
                'state_code' => $data['state_code'],
                'district' => $data['district'],
                'city' => $data['city'],
                'zipcode' => $data['zipcode'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'is_eu' => $data['is_eu'],
                'calling_code' => $data['calling_code'],
                'country_tld' => $data['country_tld'],
                'languages' => $data['languages'],
                'country_flag' => $data['country_flag'],
                'geoname_id' => $data['geoname_id'],
                'isp' => $data['isp'],
                'connection_type' => $data['connection_type'],
                'organization' => $data['organization'],
                'country_emoji' => $data['country_emoji'],
                'currency_data' => $data['currency'],
                'timezone_data' => $data['time_zone'],
                'expires_at' => now()->addDays(7)
            ]
        )->toArray();
    }

    public function getLocation(?string $ip = null): array
    {
        try {
            if (!$ip || $this->isLocalIP($ip)) {
                $ip = $this->getRandomMoroccanIP();
                Log::info('Using random test IP for local/null IP', ['ip' => $ip]);
            }

            // Check if we have cached data in database
            $cachedLocation = \App\Models\IPLocation::where('ip', $ip)
                ->where(function($query) {
                    $query->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                })
                ->first();
                
            if ($cachedLocation) {
                return $this->formatLocationResponse($cachedLocation);
            }

            $apiKey = config('services.ipgeolocation.key');
            
            $response = Http::timeout(5)
                ->retry(3, 100)
                ->get('https://api.ipgeolocation.io/ipgeo', [
                    'apiKey' => $apiKey,
                    'ip' => $ip
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $storedData = $this->storeLocationData($data);
                return $this->formatLocationResponse($storedData);
            }

            Log::warning('IPGeolocation request failed', [
                'ip' => $ip,
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            throw new \Exception('Failed to get location data');

        } catch (\Exception $e) {
            Log::error('IPGeolocation Error:', [
                'ip' => $ip,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    private function formatLocationResponse($data): array
    {
        if ($data instanceof \App\Models\IPLocation) {
            return [
                'country' => $data->country_name,
                'city' => $data->city,
                'latitude' => (float)$data->latitude,
                'longitude' => (float)$data->longitude,
                'timezone' => $data->timezone_data['name'] ?? 'UTC',
                'currency' => $data->currency_data['code'] ?? 'MAD',
                'isp' => $data->isp,
                'success' => true
            ];
        }

        return [
            'country' => $data['country_name'],
            'city' => $data['city'],
            'latitude' => (float)$data['latitude'],
            'longitude' => (float)$data['longitude'],
            'timezone' => $data['time_zone']['name'] ?? 'UTC',
            'currency' => $data['currency']['code'] ?? 'MAD',
            'isp' => $data['isp'],
            'success' => true
        ];
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

            $testIp = $this->getRandomMoroccanIP();
            $response = Http::timeout(5)
                ->retry(3, 100)
                ->get('https://api.ipgeolocation.io/ipgeo', [
                    'apiKey' => $apiKey,
                    'ip' => $testIp
                ]);

            if ($response->successful()) {
                $data = $response->json();
                $storedData = $this->storeLocationData($data);

                return [
                    'working' => true,
                    'message' => "API Working - Test location: {$storedData['city']}, {$storedData['country_name']} (ISP: {$storedData['isp']})",
                    'api_key_valid' => true,
                    'test_data' => $storedData
                ];
            }

            return [
                'working' => false,
                'message' => $response->json()['message'] ?? 'API request failed',
                'api_key_valid' => false
            ];

        } catch (\Exception $e) {
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

    private function isLocalIP(string $ip): bool
    {
        return in_array($ip, ['127.0.0.1', '::1']) || 
               preg_match('/^(192\.168\.|10\.|172\.(1[6-9]|2[0-9]|3[0-1])\.)/', $ip);
    }

    public function getRandomMoroccanIP(): string
    {
        $city = array_rand(self::MOROCCAN_TEST_IPS);
        $cityIps = self::MOROCCAN_TEST_IPS[$city];
        return $cityIps[array_rand($cityIps)];
    }
}