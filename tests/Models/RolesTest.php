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
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class RolesTest extends TestCase
{

    public function testCreate()
    {

        $rolesArray = [
            [
                'name' => 'superadmin',
                'display_name' => 'Superadmin'
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin'
            ],
            [
                'name' => 'manager',
                'display_name' => 'Manager'
            ]
        ];

        foreach($rolesArray as $roleData) {

            $role = Role::create($roleData);

            $this->assertEquals($roleData['name'], $role->name);
            $this->assertEquals($roleData['display_name'], $role->display_name);
        }
    }

    public function testRejectCreate()
    {
        $role = Role::create([
            'name' => 'superadmin',
            'display_name' => 'Super admin'
        ]);

        $this->assertNull($role);

        $role = Role::create([
            'name' => 'super/admin',
            'display_name' => 'Super/admin'
        ]);

        $this->assertNull($role);
    }

    public function testDelete()
    {
        Role::where('name', 'superadmin')->first()->delete();

        $this->assertEqual(0, Role::where('name', 'superadmin')->count());
    }

    public function testAttachingToUsers()
    {
    }

    public function testDetachingFromUsers()
    {

    }

    public function testHasRole()
    {

    }

    public function testHasRoleAliases()
    {

    }

    public function testAttachingPermissions()
    {

    }

    public function testDetachingPermissions()
    {

    }

    public function testHasPermission()
    {

    }

    public function testHasPermissionAliases()
    {

    }

}
