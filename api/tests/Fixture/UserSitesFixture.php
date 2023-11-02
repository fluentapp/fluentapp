<?php

namespace App\Test\Fixture;

/**
 * Fixture.
 */
class UserSitesFixture
{
    public string $table = 'users_sites';

    public array $records = [
        [
            'id' => '1',
            'user_id' => '1',
            'site_id' => '1',
            'role' => 'admin'
        ],

    ];
}
