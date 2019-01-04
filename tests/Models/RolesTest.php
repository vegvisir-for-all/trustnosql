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

use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class RolesTest extends ModelsTestCase
{
    public function testCount()
    {
        $this->assertEquals(4, Role::count());
    }

    public function testCreated()
    {
        $rolesData = [
            [
                'name' => 'role-first',
                'display_name' => 'Role First',
                'description' => 'Role First'
            ],
            [
                'name' => 'role-second',
                'display_name' => 'Role Second',
                'description' => 'Role Second'
            ],
            [
                'name' => 'role-third',
                'display_name' => 'Role Third',
                'description' => 'Role Third'
            ],
            [
                'name' => 'role-fourth',
                'display_name' => 'Role Fourth',
                'description' => 'Role Fourth'
            ],
        ];

        foreach ($rolesData as $roleData) {
            $role = Role::where('name', $roleData['name'])->first();

            $this->assertNotNull($role);
            $this->assertEquals($roleData['name'], $role->name);
            $this->assertEquals($roleData['display_name'], $role->display_name);
        }
    }

    public function testRejectCreateExists()
    {
        $role = Role::create(['name' => 'role-fourth']);
        $this->assertEquals(1, Role::where('name', 'role-fourth')->count());
    }

    public function testRejectCreateIllegalChars()
    {
        $role = Role::create(['name' => 'role/fourth']);
        $this->assertEquals(0, Role::where('name', 'role/fourth')->count());
    }

    public function testDelete()
    {
        Role::where('name', 'role-fourth')->delete();

        $this->assertEquals(0, Role::where('name', 'role-fourth')->count());
    }

    /**
     * ATTACHING TO USERS
     */

    public function testAttachingToUsersSingle()
    {
        $user = User::first();

        $user->attachRole('role-first');

        $this->assertEquals(1, $user->roles()->where('name', 'role-first')->count());
    }

    public function testAttachingToUsersMultiple()
    {
        $user = User::first();

        $user->attachRole('role-second,role-third');

        $this->assertEquals(3, $user->roles()->count());
    }

    public function testDetachingFromUsersSingle()
    {
        $user = User::first();

        $user->detachRole('role-first');

        $this->assertEquals(0, $user->roles()->where('name', 'role-first')->count());
    }

    public function testDetachingFromUsersMultiple()
    {
        $user = User::first();

        $user->detachRole('role-second,role-third');

        $this->assertEquals(0, $user->roles()->count());
    }

    public function testHasRoleSingleUserSingleRole()
    {
        $user = User::first();
        $user->attachRole('role-first');

        $this->assertTrue($user->hasRole('role-first'));
    }

    public function testHasRoleSingleUserAllRoles()
    {
        $user = User::first();
        $user->attachRoles('role-second,role-third');
        $this->assertTrue($user->hasRoles('role-first,role-second,role-third', true));
        $this->assertTrue($user->hasRoles(['role-first', 'role-second', 'role-third'], true));
    }

    public function testHasRoleSingleUserOneOfRoles()
    {
        $user = User::first();
        $this->assertTrue($user->hasRoles('role-third,role-fourth,role-fifth', false));
        $this->assertTrue($user->hasRoles(['role-third', 'role-fourth', 'role-fifth'], false));
    }

    public function testUserHasRoleAliases()
    {
        $user = User::first();
        $this->assertTrue($user->hasRoles('role-first'));
        $this->assertTrue($user->hasRoles(['role-first']));
        $this->assertTrue($user->isA('role-first'));
        $this->assertTrue($user->isAn('role-first'));

        // @todo Tests for facade class
    }

    /**
     * ATTACHING TO TEAMS
     */

    public function testAttachingToTeamsSingle()
    {
        $team = Team::where('name', 'team-first')->first();
        $this->assertNotNull($team);

        $team->attachRole('role-first');

        $this->assertEquals(1, $team->roles()->where('name', 'role-first')->count());
    }

    public function testAttachingToTeamsMultiple()
    {
        $team = Team::where('name', 'team-first')->first();
        $this->assertNotNull($team);

        $team->attachRoles('role-second,role-third');

        $this->assertEquals(3, $team->roles()->count());
    }

    public function testDetachingFromTeamsSingle()
    {
        $team = Team::where('name', 'team-first')->first();
        $this->assertNotNull($team);

        $team->detachRole('role-first');

        $this->assertEquals(0, $team->roles()->where('name', 'role-first')->count());
    }

    public function testDetachingFromTeamsMultiple()
    {
        $team = Team::where('name', 'team-first')->first();
        $this->assertNotNull($team);

        $team->detachRole('role-second,role-third');

        $this->assertEquals(0, $team->roles()->count());
    }

    public function testHasRoleSingleTeamSingleRole()
    {
        $team = Team::where('name', 'team-first')->first();
        $this->assertNotNull($team);
        $team->attachRole('role-first');

        $this->assertTrue($team->hasRole('role-first'));
    }

    public function testHasRoleSingleTeamAllRoles()
    {
        $team = Team::where('name', 'team-first')->first();
        $this->assertNotNull($team);

        $team->attachRoles('role-second,role-third');
        $this->assertTrue($team->hasRole('role-first,role-second,role-third', true));
        $this->assertTrue($team->hasRole(['role-first', 'role-second', 'role-third'], true));
    }

    public function testHasRoleSingleTeamOneOfRoles()
    {
        $team = Team::where('name', 'team-first')->first();
        $this->assertNotNull($team);

        $this->assertTrue($team->hasRole('role-third,role-fourth,role-fifth', false));
        $this->assertTrue($team->hasRole(['role-third', 'role-fourth', 'role-fifth'], false));
    }

    public function testTeamHasRoleAliases()
    {
        $team = Team::where('name', 'team-first')->first();
        $this->assertNotNull($team);

        $this->assertTrue($team->hasRoles('role-first'));
        $this->assertTrue($team->hasRoles(['role-first']));
        $this->assertTrue($team->isA('role-first'));
        $this->assertTrue($team->isAn('role-first'));

        // @todo Tests for facade class
    }

    /**
     * ATTACHING TO PERMISSIONS
     */

    public function testAttachingToPermissionsSingle()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $permission->attachRole('role-first');

        $this->assertEquals(1, $permission->roles()->where('name', 'role-first')->count());
    }

    public function testAttachingToPermissionsMultiple()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $permission->attachRoles('role-second,role-third');

        $this->assertEquals(3, $permission->roles()->count());
    }

    public function testDetachingFromPermissionsSingle()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $permission->detachRole('role-first');

        $this->assertEquals(0, $permission->roles()->where('name', 'role-first')->count());
    }

    public function testDetachingFromPermissionsMultiple()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $permission->detachRole('role-second,role-third');

        $this->assertEquals(0, $permission->roles()->count());
    }

    public function testHasRoleSinglePermissionSingleRole()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $permission->attachRole('role-first');

        $this->assertTrue($permission->hasRole('role-first'));
    }

    public function testHasRoleSinglePermissionAllRoles()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $permission->attachRoles('role-second,role-third');
        $this->assertTrue($permission->hasRole('role-first,role-second,role-third', true));
        $this->assertTrue($permission->hasRole(['role-first', 'role-second', 'role-third'], true));
    }

    public function testHasRoleSinglePermissionOneOfRoles()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $this->assertTrue($permission->hasRole('role-third,role-fourth,role-fifth', false));
        $this->assertTrue($permission->hasRole(['role-third', 'role-fourth', 'role-fifth'], false));
    }

    public function testPermissionHasRoleAliases()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $this->assertNotNull($permission);

        $this->assertTrue($permission->hasRoles('role-first'));
        $this->assertTrue($permission->hasRoles(['role-first']));
        $this->assertTrue($permission->isA('role-first'));
        $this->assertTrue($permission->isAn('role-first'));

        // @todo Tests for facade class
    }
}
