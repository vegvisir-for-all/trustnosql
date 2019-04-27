<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests\Events;

use Illuminate\Support\Facades\Cache;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

/**
 * @internal
 * @coversNothing
 */
final class PermissionsEventsTest extends EventsTestCase
{
    public function testPermissionsExist()
    {
        $this->assertSame(1, Permission::count());
    }

    public function testRolesExist()
    {
        $this->assertSame(1, Role::count());
    }

    public function testTeamsExist()
    {
        $this->assertSame(1, Team::count());
    }

    public function testUsersExist()
    {
        $this->assertSame(5, User::count());
    }

    public function testRolesAttachedEvent()
    {
        $key = 'permissions-roles-attached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', $this->roleName)->first();
        $permission = Permission::where('name', $this->permissionName)->first();

        $permission->attachRole($role->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testRolesDetachedEvent()
    {
        $role = Role::where('name', $this->roleName)->first();
        $permission = Permission::where('name', $this->permissionName)->first();

        $key = 'permissions-roles-detached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', $this->roleName)->first();
        $permission = Permission::where('name', $this->permissionName)->first();

        $permission->detachRole($role->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testUsersAttachedEvent()
    {
        $key = 'permissions-users-attached-event';

        Cache::put($key, false, 999999);

        $user = User::where(1)->first();
        $permission = Permission::where('name', $this->permissionName)->first();

        $permission->attachUser($user->email);

        $this->assertTrue(Cache::pull($key));
    }

    public function testUsersDetachedEvent()
    {
        $key = 'permissions-users-detached-event';

        Cache::put($key, false, 999999);

        $user = User::where(1)->first();
        $permission = Permission::where('name', $this->permissionName)->first();

        $permission->detachUser($user->email);

        $this->assertTrue(Cache::pull($key));
    }
}
