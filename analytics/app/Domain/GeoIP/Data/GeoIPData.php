<?php

namespace App\Domain\GeoIP\Data;

use Selective\ArrayReader\ArrayReader;

/**
 * Data Model.
 */
final class GeoIPData
{

    public ?string $country = null;
    
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

        $this->country = $reader->findString('country');
        $this->state = $reader->findString('state');
        $this->city = $reader->findString('city');
    }
}
