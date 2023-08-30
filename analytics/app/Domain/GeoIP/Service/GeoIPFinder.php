<?php

namespace App\Domain\GeoIP\Service;

use App\Domain\GeoIP\Data\GeoIPData;
use Illuminate\Support\Facades\Log;

class GeoIPFinder {

    private $ipInfo;

    public function __construct(\App\Support\IpInformation $ipInfo) {
        $this->ipInfo = $ipInfo;
    }

    /**
     * Gets IP info
     * @return GeoIPData
     */
    public function getInfoByIp(string $ipAddress): GeoIPData {
        $details = $this->ipInfo->getDetails($ipAddress);
        $geoData = new GeoIPData([
            'country' => $details->countryName,
            'city' => $details->cityName,
            'state' => $details->regionName
        ]);

        return $geoData;
    }
}
