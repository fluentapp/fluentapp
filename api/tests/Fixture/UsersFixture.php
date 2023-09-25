<?php

namespace App\Test\Fixture;

/**
 * Fixture.
 */
class UsersFixture
{
    public string $table = 'users';

    public array $records = [
        [
            'id' => '1',
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'whatever',
           'created_at' => '2023-08-03 05:24:34'
        ],

    ];
}
