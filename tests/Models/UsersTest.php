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
        $permission->attachUser($user->email);
        $this->assertEquals(1, $permission->users()->where('email', $user->email)->count());
    }

    public function testAttachingToPermissionsMultiple()
    {
        $second = User::skip(1)->first();
        $third = User::skip(2)->first();
        $permission = Permission::where('name', 'permission/first')->first();
        $permission->attachUser($second->email . ',' . $third->email);
        $this->assertEquals(3, $permission->users()->count());
    }

    public function testDetachingFromPermissionsSingle()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $first = User::first();
        $permission->detachUser($first->email);
        $this->assertEquals(0, $permission->users()->where('email', $first->email)->count());
    }

    public function testDetachingFromPermissionsMultiple()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $second = User::skip(1)->first();
        $third = User::skip(2)->first();
        $permission->detachUser($second->email . ',' . $third->email);
        $this->assertEquals(0, $permission->users()->count());
    }

    public function testPermissionHasUserSinglePermissionSingleUser()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $first = User::first();
        $permission->attachUser($first->email);
        $this->assertTrue($permission->hasUser($first->email));
    }

    public function testPermissionHasUserAliasesString()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $first = User::first();
        $this->assertTrue($permission->hasUsers($first->email));
    }

    public function testPermissionHasUserAliasesArray()
    {
        $permission = Permission::where('name', 'permission/first')->first();
        $first = User::first();
        $this->assertTrue($permission->hasUsers([$first->email]));
    }

    /**
     * ATTACHING TO ROLES
     */

    public function testAttachingToRolesSingle()
    {
        $user = User::first();
        $role = Role::where('name', 'role-first')->first();
        $role->attachUser($user->email);
        $this->assertEquals(1, $role->users()->where('email', $user->email)->count());
    }

    public function testAttachingToRolesMultiple()
    {
        $second = User::skip(1)->first();
        $third = User::skip(2)->first();
        $role = Role::where('name', 'role-first')->first();
        $role->attachUser($second->email . ',' . $third->email);
        $this->assertEquals(3, $role->users()->count());
    }

    public function testDetachingFromRolesSingle()
    {
        $role = Role::where('name', 'role-first')->first();
        $first = User::first();
        $role->detachUser($first->email);
        $this->assertEquals(0, $role->users()->where('email', $first->email)->count());
    }

    public function testDetachingFromRolesMultiple()
    {
        $role = Role::where('name', 'role-first')->first();
        $second = User::skip(1)->first();
        $third = User::skip(2)->first();
        $role->detachUser($second->email . ',' . $third->email);
        $this->assertEquals(0, $role->users()->count());
    }

    public function testRoleHasUserSingleRoleSingleUser()
    {
        $role = Role::where('name', 'role-first')->first();
        $first = User::first();
        $role->attachUser($first->email);
        $this->assertTrue($role->hasUser($first->email));
    }

    public function testRoleHasUserAliasesString()
    {
        $role = Role::where('name', 'role-first')->first();
        $first = User::first();
        $this->assertTrue($role->hasUsers($first->email));
    }

    public function testRoleHasUserAliasesArray()
    {
        $role = Role::where('name', 'role-first')->first();
        $first = User::first();
        $this->assertTrue($role->hasUsers([$first->email]));
    }

    /**
     * ATTACHING TO TEAMS
     */

    public function testAttachingToTeamsSingle()
    {
        $user = User::first();
        $team = Team::where('name', 'team-first')->first();
        $team->attachUser($user->email);
        $this->assertEquals(1, $team->users()->where('email', $user->email)->count());
    }

    public function testAttachingToTeamsMultiple()
    {
        $second = User::skip(1)->first();
        $third = User::skip(2)->first();
        $team = Team::where('name', 'team-first')->first();
        $team->attachUser($second->email . ',' . $third->email);
        $this->assertEquals(3, $team->users()->count());
    }

    public function testDetachingFromTeamsSingle()
    {
        $team = Team::where('name', 'team-first')->first();
        $first = User::first();
        $team->detachUser($first->email);
        $this->assertEquals(0, $team->users()->where('email', $first->email)->count());
    }

    public function testDetachingFromTeamsMultiple()
    {
        $team = Team::where('name', 'team-first')->first();
        $second = User::skip(1)->first();
        $third = User::skip(2)->first();
        $team->detachUser($second->email . ',' . $third->email);
        $this->assertEquals(0, $team->users()->count());
    }

    public function testTeamHasUserSingleTeamSingleUser()
    {
        $team = Team::where('name', 'team-first')->first();
        $first = User::first();
        $team->attachUser($first->email);
        $this->assertTrue($team->hasUser($first->email));
    }

    public function testTeamHasUserAliasesString()
    {
        $team = Team::where('name', 'team-first')->first();
        $first = User::first();
        $this->assertTrue($team->hasUsers($first->email));
    }

    public function testTeamHasUserAliasesArray()
    {
        $team = Team::where('name', 'team-first')->first();
        $first = User::first();
        $this->assertTrue($team->hasUsers([$first->email]));
    }
}
