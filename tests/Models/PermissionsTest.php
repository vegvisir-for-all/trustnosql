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

use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\TrustNoSql;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class PermissionsTest extends ModelsTestCase
{
    public function testCount()
    {
        $this->assertEquals(4, Permission::count());
    }

    public function testCreated()
    {
        $permissionsData = [
            [
                'name' => 'permission/first',
                'display_name' => 'Permission First',
                'description' => 'Permission First'
            ],
            [
                'name' => 'permission/second',
                'display_name' => 'Permission Second',
                'description' => 'Permission Second'
            ],
            [
                'name' => 'permission/third',
                'display_name' => 'Permission Third',
                'description' => 'Permission Third'
            ],
            [
                'name' => 'permission/fourth',
                'display_name' => 'Permission Fourth',
                'description' => 'Permission Fourth'
            ],
        ];

        foreach ($permissionsData as $permissionData) {
            $permission = Permission::where('name', $permissionData['name'])->first();

            $this->assertNotNull($permission);
            $this->assertEquals($permissionData['name'], $permission->name);
            $this->assertEquals($permissionData['display_name'], $permission->display_name);
        }
    }

    public function testRejectCreateExists()
    {
        $permission = Permission::create(['name' => 'permission/first']);
        $this->assertEquals(1, $permission->where('name', 'permission/first')->count());
    }

    public function testRejectCreateWildcard()
    {
        $permission = Permission::create(['name' => 'permission/*']);
        $permission2 = Permission::create(['name' => 'permission/all']);

        $this->assertNull($permission);
        $this->assertNull($permission2);
    }

    public function testDelete()
    {
        Permission::where('name', 'permission/fourth')->delete();

        $this->assertEquals(0, Permission::where('name', 'permission/fourth')->count());
    }

    public function testAttachingToUsers()
    {
        $user = User::first();

        $user->attachPermission('permission/first');

        $this->assertEquals(1, $user->permissions->where('name', 'permission/first')->count());
    }

    public function testDetachingFromUsers()
    {
        $user = User::first();

        $user->detachPermission('permission/first');

        $this->assertEquals(0, $user->permissions->where('name', 'permission/first')->count());
    }

    public function testHasPermissionWhenDoesNotHave()
    {
        $user = User::first();

        $this->assertFalse($user->hasPermission('permission/first'));
    }

    public function testHasPermissionWhenHas()
    {
        $user = User::first();
        $user->attachPermission('permission/first');

        $this->assertTrue($user->hasPermission('permission/first'));
    }

    public function testHasWildcardPermissions()
    {
        $user = User::first();

        $user->attachPermissions('permission/first,permission/second,permission/third');

        $this->assertTrue($user->hasPermission('permission/*'));
        $this->assertTrue($user->hasPermission('permission/all'));
    }

    public function testDoesntHaveWildcardPermissions()
    {
        $user = User::first();
        $user->detachPermission('permission/third');

        $this->assertEquals(2, $user->permissions()->count());

        $this->assertFalse($user->hasPermission('permission/*'));
        $this->assertFalse($user->hasPermission('permission/all'));
    }

    public function testHasPermissionAliases()
    {
        $user = User::first();

        $this->assertTrue($user->hasPermissions('permission/first'));
        $this->assertTrue($user->does('permission/first'));
        $this->assertFalse($user->hasPermission('permission/everything'));
    }

    public function testMagicCanShouldBeTrue()
    {
        $user = User::first();

        $this->assertTrue($user->canFirstPermission());
    }

    public function testMagicCanShouldBeFalse()
    {
        $user = User::first();

        $this->assertFalse($user->canRemovePermission());
    }
}
