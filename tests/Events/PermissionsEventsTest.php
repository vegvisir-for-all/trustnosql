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

class PermissionsEventsTest extends Events
{
    public function testRolesAttachedEvent()
    {
        self::clearAfterModelTests();
        self::setObservers();

        $key = 'permissions-roles-attached-event';

        Cache::put($key, false, 999999);

        $role = Role::create(['name' => 'admin']);
        $permission = Permission::create(['name' => 'everything/do']);

        $permission->attachRole($role->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testRolesDetachedEvent()
    {
        self::setObservers();

        $key = 'permissions-roles-detached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $permission = Permission::where('name', 'everything/do')->first();

        $permission->detachRole($role->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testUsersAttachedEvent()
    {
        self::setObservers();

        $key = 'permissions-users-attached-event';

        Cache::put($key, false, 999999);

        $user = User::where(1)->first();
        $permission = Permission::where('name', 'everything/do')->first();

        $permission->attachUser($user->email);

        $this->assertTrue(Cache::pull($key));
    }

    public function testUsersDetachedEvent()
    {
        self::setObservers();

        $key = 'permissions-users-detached-event';

        Cache::put($key, false, 999999);

        $user = User::where(1)->first();
        $permission = Permission::where('name', 'everything/do')->first();

        $permission->detachUser($user->email);

        $this->assertTrue(Cache::pull($key));
    }
}
