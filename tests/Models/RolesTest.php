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

        $this->assertEquals(0, $user->roles()->where('name', 'admin')->count());

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
        $user = User::first();

        $this->assertTrue($user->hasRoles('admin'));
        $this->assertTrue($user->isA('admin'));
        $this->assertTrue($user->isAn('admin'));
    }

    public function testAttachingPermissions()
    {

        Permission::where(1)->delete();

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

        foreach($permissionsArray as $key => $permissionData) {
            $permission[$key] = Permission::create($permissionData);
        }

        $admin = Role::where('name', 'admin')->first();

        $superadmin = Role::where('name', 'superadmin')->first();

        $admin->attachPermission('namespace/task');
        $superadmin->attachPermission('namespace/another,namespace/third');

        $this->assertEquals(1, $admin->permissions()->count());
        $this->assertEquals(2, $superadmin->permissions()->count());


    }

    public function testHasPermission()
    {
        $admin = Role::where('name', 'admin')->first();
        $superadmin = Role::where('name', 'superadmin')->first();

        $this->assertTrue($admin->hasPermission('namespace/task'));
        $this->assertTrue($superadmin->hasPermission('namespace/another,namespace/third', true));
        $this->assertTrue($admin->hasPermission('namespace/task,namespace/third', false));
        //failure
        $this->assertFalse($admin->hasPermission('namespace/task,namespace/third', true));
    }

    public function testHasPermissionAliases()
    {
        $admin = Role::where('name', 'admin')->first();
        $superadmin = Role::where('name', 'superadmin')->first();

        $this->assertTrue($admin->hasPermissions('namespace/task'));
        $this->assertTrue($admin->hasPermissions('namespace/*'));
    }

    public function testDetachingPermissions()
    {
        $admin = Role::where('name', 'admin')->first();
        $superadmin = Role::where('name', 'superadmin')->first();

        $admin->detachPermission('namespace/task');
        $superadmin->detachPermission('namespace/*');

        $this->assertEquals(0, $admin->permissions()->where('name', 'admin')->count());
        $this->assertEquals(0, $superadmin->permissions()->where('name', 'like', 'namespace/%')->count());
    }

}
