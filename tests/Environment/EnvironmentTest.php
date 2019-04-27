<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests\Environment;

use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

/**
 * @internal
 * @coversNothing
 */
final class EnvironmentTest extends EnvironmentTestCase
{
    public function testMongoConnection()
    {
        $defaultDriver = config('database.default');
        $this->assertSame('mongodb', $defaultDriver);
    }

    public function testUsersCount()
    {
        $this->assertSame(5, User::count());
    }

    public function testUsersCreatedName()
    {
        $users = test_seed_users();
        foreach ($users as $seedUser) {
            $user = User::where('name', $seedUser['name'])->first();
            $this->assertSame($seedUser['name'], $user->name);
        }
    }

    public function testUsersCreatedEmail()
    {
        $users = test_seed_users();
        foreach ($users as $seedUser) {
            $user = User::where('name', $seedUser['name'])->first();
            $this->assertSame($seedUser['email'], $user->email);
        }
    }
}
