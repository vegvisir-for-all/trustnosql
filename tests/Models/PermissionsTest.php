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

use Vegvisir\TrustNoSql\Tests\TestCase;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class PermissionsTest extends TestCase
{

    public function testDeleteAll()
    {
        Permission::where(1)->delete();
        $this->assertCount(0, Permission::where('name', 'namespace/task')->get()->toArray());
    }

    public function testCreate()
    {

        $permission = Permission::create([
            'name' => 'namespace/task'
        ]);
        $this->assertSame('namespace/task', $permission->name);

        $permission = Permission::create([
            'name' => 'namespace/task'
        ]);
        $this->assertCount(1, Permission::where('name', 'namespace/task')->get()->toArray());

        $permission = Permission::create([
            'name' => 'namespace/*'
        ]);
        $this->assertNull($permission);

        $permission = Permission::create([
            'name' => 'namespace-task'
        ]);
        $this->assertNull($permission);

    }

    public function testDeleteSingle()
    {
        Permission::where('namespace/task')->delete();
        $this->assertCount(0, Permission::where(1)->get()->toArray());
    }

    public function fakeFailedTest()
    {
        $this->assertTrue(false);
    }

    // public function testAttaching()
    // {
    //     $permissionNamespaceTask = Permission::create([
    //         'name' => 'namespace/task'
    //     ]);

    //     $permissionNamespaceAnother = Permission::create([
    //         'name' => 'namespace/another'
    //     ]);

    //     $userFirst = User::sortBy('name')->first();
    //     $userLast = User::sortBy('name')->last();

    //     $userFirst->attachPermissions('namespace/task');
    //     $userLast->attachPermissions('namespace/task,namespace/another');
    // }

    // public function testDetaching()
    // {

    // }

    // public function testHasPermission()
    // {

    // }

    // public function testHasPermissionAliases()
    // {

    // }

    // public function testMagicCan()
    // {

    // }

    // public function testHasMultipleOr()
    // {

    // }

    // public function testHasMultipleAnd()
    // {

    // }
}
