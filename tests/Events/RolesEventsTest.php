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

class RolesEventsTest extends Events
{
    public function testPermissionsAttachedEvent()
    {
        self::setObservers();

        $key = 'roles-permissions-attached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $permission = Permission::where(['name' => 'everything/do'])->first();

        $role->attachPermission($permission->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testPermissionsDetachedEvent()
    {
        self::setObservers();

        $key = 'roles-permissions-detached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $permission = Permission::where('name', 'everything/do')->first();

        $role->detachPermission($permission->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testTeamsAttachedEvent()
    {
        self::setObservers();

        $key = 'roles-teams-attached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $team = Team::create(['name' => 'vegvisir']);

        $role->attachTeam($team->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testTeamsDetachedEvent()
    {
        self::setObservers();

        $key = 'roles-teams-detached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $team = Team::create(['name' => 'vegvisir']);

        $role->detachTeam($team->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testUsersAttachedEvent()
    {
        self::setObservers();

        $key = 'roles-users-attached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $user = User::where(1)->first();

        $role->attachUser($user->email);

        $this->assertTrue(Cache::pull($key));
    }

    public function testUsersDetachedEvent()
    {
        self::setObservers();

        $key = 'roles-users-detached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $user = User::where(1)->first();

        $role->detachUser($user->email);

        $this->assertTrue(Cache::pull($key));
    }
}
