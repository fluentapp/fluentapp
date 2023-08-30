<?php

namespace App\Support;

class IpInformation
{
    protected $apiUrl = 'https://freeipapi.com/api/json/';

    public function getDetails(string $ipAddress)
    {
        $url = $this->apiUrl . $ipAddress;
        $response = file_get_contents($url);
        $data = json_decode($response);

        return $data;
    }
}
