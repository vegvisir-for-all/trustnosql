<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests\Models;

use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class UsersTest extends ModelsTestCase
{
    public function testCount()
    {
        $this->assertEquals(5, User::count());
    }

    /**
     * ATTACHING TO PERMISSIONS
     */

    public function testAttachingToPermissionsSingle()
    {
        $user = User::first();
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $permission->attachUser($user->email);

        $this->assertEquals(1, $permission->users()->where('email', $user->email)->count());
    }

    public function testAttachingToPermissionsMultiple()
    {
        $first = User::first();
        $second = User::skip(1)->first();
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $permission->attachUser($first->email . ',' . $second->email);

        $this->assertEquals(3, $permission->users()->count());
    }

    public function testDetachingFromPermissionsSingle()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $first = User::first();

        $permission->detachUser($first->email);

        $this->assertEquals(0, $permission->users()->where('email', $first->email)->count());
    }

    public function testDetachingFromPermissionsMultiple()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $first = User::first();
        $second = User::skip(1)->first();

        $permission->detachUser($first->email . ',' . $second->email);

        $this->assertEquals(0, $permission->users()->count());
    }

    public function testHasRoleSinglePermissionSingleUser()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $first = User::first();

        $permission->attachUser($first->email);

        $this->assertTrue($permission->hasUser($first->email));
    }

    public function testPermissionHasUserAliases()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $first = User::first();

        $this->assertTrue($permission->hasUsers($first->email));
        $this->assertTrue($permission->hasUsers([$first->email]));

        // @todo Tests for facade class
    }
}
