<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

readonly class GeoIPService
{
    // Moroccan Test IPs with verified locations
    private const MOROCCAN_TEST_IPS = [
        'marrakesh' => '105.158.107.135',  // Marrakesh, verified working
        'casablanca' => '105.158.106.23',  // Casablanca region
        'rabat' => '105.157.3.214',        // Rabat region
        'tangier' => '196.217.12.241',     // Tangier region
        'agadir' => '105.157.96.120'       // Agadir region
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

    public function getLocation(?string $ip = null): array
    {
        try {
            // Handle null IP or local IP
            if (!$ip || $this->isLocalIP($ip)) {
                $ip = self::MOROCCAN_TEST_IPS['marrakesh']; // Use verified Marrakesh IP
                Log::info('Using test IP for local/null IP', ['ip' => $ip]);
            }

            $apiKey = config('services.ipgeolocation.key');
            
            $response = Http::timeout(5)
                ->retry(3, 100)
                ->get('https://api.ipgeolocation.io/ipgeo', [
                    'apiKey' => $apiKey,
                    'ip' => $ip,
                    'fields' => 'geo,time_zone,currency'
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'country' => $data['country_name'],
                    'city' => $data['city'],
                    'latitude' => (float)$data['latitude'],
                    'longitude' => (float)$data['longitude'],
                    'timezone' => $data['time_zone']['name'],
                    'currency' => $data['currency']['code'],
                    'isp' => $data['isp'] ?? null,
                    'success' => true
                ];
            }

            Log::warning('IPGeolocation request failed', [
                'ip' => $ip,
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            return self::DEFAULT_LOCATION;

        } catch (\Exception $e) {
            Log::error('IPGeolocation Error:', [
                'ip' => $ip,
                'error' => $e->getMessage()
            ]);

            return self::DEFAULT_LOCATION;
        }
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

            // Test with our verified Marrakesh IP
            $response = Http::timeout(5)
                ->retry(3, 100)
                ->get('https://api.ipgeolocation.io/ipgeo', [
                    'apiKey' => $apiKey,
                    'ip' => self::MOROCCAN_TEST_IPS['marrakesh']
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'working' => true,
                    'message' => "API Working - Test location: {$data['city']}, {$data['country_name']} (ISP: {$data['isp']})",
                    'api_key_valid' => true,
                    'test_data' => $data
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
        return self::MOROCCAN_TEST_IPS[array_rand(self::MOROCCAN_TEST_IPS)];
    }
} 