<?php

namespace App\Domain\GeoIP\Data;

use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class GeoIPData
{
    public ?string $country = null;

    public ?string $country_code = null;

    public ?string $state = null;

    public ?string $city = null;



    /**
     * The constructor.
     *
     * @param array $data The data
     */
    public function __construct(array $data = [])
    {
        $reader = new ArrayReader($data);

        $this->country = ($data['country'] !== '-') ?
                        $reader->findString('country') :
                        null;
        $this->country_code = ($data['country_code'] !== '-') ?
                        $reader->findString('country_code') :
                        null;
        $this->state = ($data['state'] !== '-') ?
                        $reader->findString('state') :
                        null;
        $this->city = ($data['city'] !== '-') ?
                        $reader->findString('city') :
                        null;
    }
}
