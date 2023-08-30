<?php

namespace App\Domain\GeoIP\Service;

use App\Domain\GeoIP\Data\GeoIPData;
use Illuminate\Support\Facades\Log;

class GeoIPFinder
{
    protected $apiUrl = 'https://freeipapi.com/api/json/';

    /**
     * Gets IP info
     * @return GeoIPData
     */
    public function getInfoByIp(string $ipAddress): GeoIPData
    {
        $details = $this->getDetails($ipAddress);
        $geoData = new GeoIPData([
            'country' => $details->countryName,
            'city' => $details->cityName,
            'state' => $details->regionName
        ]);

        return $geoData;
    }


    private function getDetails(string $ipAddress)
    {
        $url = $this->apiUrl . $ipAddress;
        $response = file_get_contents($url);
        return json_decode($response);
    }
}
