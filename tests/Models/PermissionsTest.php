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

use Vegvisir\TrustNoSql\TrustNoSql;
use Vegvisir\TrustNoSql\Tests\TestCase;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class PermissionsTest extends TestCase
{
    public function testCreate()
    {
        $permissionsArray = [
            [
                'name' => 'namespace/task',
                'display_name' => 'Namespace Task'
            ],
            [
                'name' => 'namespace/another',
                'display_name' => 'Namespace Another'
            ],
            [
                'name' => 'namespace/third',
                'display_name' => 'Namespace Third'
            ]
        ];

        foreach ($permissionsArray as $permissionData) {
            $permission = Permission::create($permissionData);

            $this->assertEquals($permissionData['name'], $permission->name);
            $this->assertEquals($permissionData['display_name'], $permission->display_name);
        }
    }

    public function testRejectCreate()
    {
        $permission = Permission::create([
            'name' => 'namespace/task2'
        ]);

        $this->assertNotNull($permission);

        $permission = Permission::create([
            'name' => 'namespace/*'
        ]);

        $this->assertNull($permission);

        $permission = Permission::create([
            'name' => 'namespace/all'
        ]);

        $this->assertNull($permission);
    }

    public function testDelete()
    {
        $permission = Permission::where('name', 'namespace/task');
        $permission->delete();

        $this->assertEquals(0, Permission::where('name', 'namespace/task')->count());
    }

    public function testAttachingToUsers()
    {
        $user = User::first();

        $permissionsArray = [
            [
                'name' => 'namespace/task',
                'display_name' => 'Namespace Task'
            ],
            [
                'name' => 'namespace/another',
                'display_name' => 'Namespace Another'
            ],
            [
                'name' => 'namespace/third',
                'display_name' => 'Namespace Third'
            ]
        ];

        foreach ($permissionsArray as $permissionData) {
            Permission::create($permissionData);
        }

        $user->attachPermission('namespace/task');

        $hasPermissionCount = $user->permissions->where('name', 'namespace/task')->count();

        $this->assertEquals(1, $hasPermissionCount);
    }

    public function testDetachingFromUsers()
    {
        $user = User::first();

        $user->detachPermission('namespace/task');

        $hasPermissionCount = $user->permissions->where('name', 'namespace/task')->count();

        $this->assertEquals(0, $hasPermissionCount);
    }

    public function testHasPermission()
    {
        $user = User::first();

        $this->assertFalse($user->hasPermission('namespace/task'));

        $user->attachPermission('namespace/task');

        $this->assertTrue($user->hasPermission('namespace/task'));
    }

    public function testHasPermissionAliases()
    {
        $user = User::first();

        $this->assertTrue($user->hasPermissions('namespace/task'));
        $this->assertTrue($user->can('namespace/task'));
        $this->assertTrue($user->hasPermission('namespace/*'));
        $this->assertTrue($user->hasPermission('namespace/all'));
        $this->assertFalse($user->hasPermission('namespace/everything'));
    }

    public function testMagicCan()
    {
        $user = User::first();

        $this->assertTrue($user->canTaskNamespace());
        $this->assertFalse($user->canRemoveNamespace());
    }
}
