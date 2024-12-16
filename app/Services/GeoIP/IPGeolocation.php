<?php

namespace App\Services\GeoIP;

use Exception;
use Illuminate\Support\Facades\Http;
use Torann\GeoIP\Services\AbstractService;

class IPGeolocation extends AbstractService
{
    /**
     * Http client instance.
     */
    protected $client;

    /**
     * The "booting" method of the service.
     */
    public function boot()
    {
        $this->client = Http::baseUrl('https://api.ipgeolocation.io/')
            ->withQueryParameters([
                'apiKey' => $this->config('key'),
            ]);
    }

    /**
     * Get location by IP.
     */
    public function locate($ip)
    {
        // Get data from client
        try {
            $response = $this->client->get('ipgeo', [
                'ip' => $ip,
                'fields' => 'geo,time_zone,currency'
            ]);

            if (!$response->successful()) {
                throw new Exception('Request failed (' . $response->status() . ')');
            }

            $data = $response->json();

            return [
                'ip' => $ip,
                'iso_code' => $data['country_code2'],
                'country' => $data['country_name'],
                'city' => $data['city'],
                'state' => $data['state_prov'],
                'state_name' => $data['state_prov'],
                'postal_code' => $data['zipcode'],
                'lat' => $data['latitude'],
                'lon' => $data['longitude'],
                'timezone' => $data['time_zone']['name'],
                'continent' => $data['continent_name'],
                'currency' => $data['currency']['code'],
                'default' => false,
            ];
        } catch (Exception $e) {
            \Log::error('IPGeolocation Error:', [
                'ip' => $ip,
                'error' => $e->getMessage()
            ]);

            return $this->getDefault();
        }
    }

    /**
     * Update function for service.
     */
    public function update()
    {
        return '';
    }
} 