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

        $this->assertEquals(0, Role::where('name', 'superadmin')->count());
    }

    public function testAttachingToUsers()
    {
        $user = User::first();

        $user->attachRole('admin');

        $this->assertEquals(1, $user->roles()->where('name', 'admin')->count());

        Role::create(['name' => 'superadmin']);

        $user->attachRole('superadmin,manager');

        $this->assertEquals(3, $user->roles()->count());
    }

    public function testDetachingFromUsers()
    {
        $user = User::first();

        $user->detachRole('admin');

        $this->assertEquals(2, $user->roles()->where('name', 'admin')->count());

        $user->detachRole('superadmin,manager');

        $this->assertEquals(0, $user->roles()->count());
    }

    public function testHasRole()
    {
        $user = User::first();

        $user->attachRole('admin');

        $this->assertTrue($user->hasRole('admin'));

        $user->attachRole('superadmin');

        $this->assertTrue($user->hasRole('admin,superadmin', true));

        $this->assertTrue($user->hasRole('admin,manager', false));

        // Failure
        $this->assertFalse($user->hasRole('admin,manager', true));
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
