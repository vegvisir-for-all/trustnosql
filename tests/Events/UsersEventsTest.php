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

class UsersEventsTest extends Events
{
    public function testPermissionsAttachedEvent()
    {
        self::setObservers();

        $key = 'users-permissions-attached-event';

        Cache::put($key, false, 999999);

        $user = User::where(1)->first();
        $permission = Permission::where(['name' => 'everything/do'])->first();

        $user->attachPermission($permission->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testPermissionsDetachedEvent()
    {
        self::setObservers();

        $key = 'users-permissions-detached-event';

        Cache::put($key, false, 999999);

        $user = User::where(1)->first();
        $permission = Permission::where('name', 'everything/do')->first();

        $user->detachPermission($permission->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testRolesAttachedEvent()
    {
        self::setObservers();

        $key = 'users-roles-attached-event';

        Cache::put($key, false, 999999);

        $role = Role::create(['name' => 'admin']);
        $user = User::where(1)->first();

        $user->attachRole($role->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testRolesDetachedEvent()
    {
        self::setObservers();

        $key = 'users-roles-detached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $user = User::where(1)->first();

        $user->detachRole($role->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testTeamsAttachedEvent()
    {
        self::setObservers();

        $key = 'users-teams-attached-event';

        Cache::put($key, false, 999999);

        $user = User::where(1)->first();
        $team = Team::create(['name' => 'vegvisir']);

        $user->attachTeam($team->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testTeamsDetachedEvent()
    {
        self::setObservers();

        $key = 'roles-teams-detached-event';

        Cache::put($key, false, 999999);

        $user = User::where(1)->first();
        $team = Team::create(['name' => 'vegvisir']);

        $user->detachTeam($team->name);

        $this->assertTrue(Cache::pull($key));
    }
}
