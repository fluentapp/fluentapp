<?php

namespace App\Test\Fixture;

/**
 * Fixture.
 */
class SiteFixture
{
    public string $table = 'sites';

    public array $records = [
        [
            'id' => '1',
            'fqdn' => 'moe.com',
            'active' => 1,
            'timezone' => 'Asia/Beirut',
            'created_at' => '2023-08-03 05:24:34'
        ],

    ];
}
