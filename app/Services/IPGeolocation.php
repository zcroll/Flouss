<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Arr;
use Torann\GeoIP\Services\AbstractService;

class IPGeolocation extends AbstractService
{
    /**
     * @param string $ip
     *
     * @return array
     * @throws Exception
     */
    public function locate($ip)
    {
        // Get data from IPGeolocation
        $data = $this->getRaw($ip);

        // Verify the data is valid
        if (empty($data)) {
            return $this->getDefault();
        }

        return [
            'ip' => $ip,
            'iso_code' => $data['country_code2'],
            'country' => $data['country_name'],
            'city' => $data['city'],
            'state' => $data['state_code'],
            'state_name' => $data['state_prov'],
            'postal_code' => $data['zipcode'],
            'lat' => $data['latitude'],
            'lon' => $data['longitude'],
            'timezone' => $data['time_zone']['name'],
            'continent' => $this->getContinent($data['country_code2']),
            'currency' => $data['currency']['code'],
        ];
    }

    /**
     * Get the raw GeoIP info using IPGeolocation.
     *
     * @param string $ip
     *
     * @return array|null
     * @throws Exception
     */
    protected function getRaw($ip)
    {
        try {
            $response = $this->client->get("https://api.ipgeolocation.io/ipgeo", [
                'query' => [
                    'apiKey' => $this->config('key'),
                    'ip' => $ip,
                ],
            ]);

            return json_decode($response->getBody(), true);
        } catch (Exception $e) {
            throw new Exception('IPGeolocation: ' . $e->getMessage());
        }
    }
} 