<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests\Events;

use Illuminate\Support\Facades\Cache;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class TeamsEventsTest extends Events
{
    public function testRolesAttachedEvent()
    {
        self::setObservers();

        $key = 'teams-roles-attached-event';

        Cache::put($key, false, 999999);

        $role = Role::create(['name' => 'admin']);
        $team = Team::create(['name' => 'vegvisir3']);

        $team->attachRole($role->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testRolesDetachedEvent()
    {
        self::setObservers();

        $key = 'teams-roles-detached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $team = Team::where('name', 'vegvisir3')->first();

        $team->detachRole($role->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testUsersAttachedEvent()
    {
        self::setObservers();

        $key = 'teams-users-attached-event';

        Cache::put($key, false, 999999);

        $team = Team::where('name', 'vegvisir3')->first();
        $user = User::where(1)->first();

        $team->attachUser($user->email);

        $this->assertTrue(Cache::pull($key));
    }

    public function testUsersDetachedEvent()
    {
        self::setObservers();

        $key = 'teams-users-detached-event';

        Cache::put($key, false, 999999);

        $team = Team::where('name', 'vegvisir2')->first();
        $user = User::where(1)->first();

        $team->detachUser($user->email);

        $this->assertTrue(Cache::pull($key));
    }
}
