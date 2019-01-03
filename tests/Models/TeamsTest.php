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

class TeamsTest extends ModelsTestCase
{
    public function testCount()
    {
        $this->assertEquals(4, Team::count());
    }

    public function testCreated()
    {
        $teamsData = [
            [
                'name' => 'team-first',
                'display_name' => 'Team First',
                'description' => 'Team First'
            ],
            [
                'name' => 'team-second',
                'display_name' => 'Team Second',
                'description' => 'Team Second'
            ],
            [
                'name' => 'team-third',
                'display_name' => 'Team Third',
                'description' => 'Team Third'
            ],
            [
                'name' => 'team-fourth',
                'display_name' => 'Team Fourth',
                'description' => 'Team Fourth'
            ],
        ];

        foreach ($teamsData as $teamData) {
            $team = Team::where('name', $teamData['name'])->first();

            $this->assertNotNull($team);
            $this->assertEquals($teamData['name'], $team->name);
            $this->assertEquals($teamData['display_name'], $team->display_name);
        }
    }

    public function testRejectCreateExists()
    {
        $team = Team::create(['name' => 'team-fourth']);
        $this->assertEquals(1, Team::where('name', 'team-fourth')->count());
    }

    public function testRejectCreateIllegalChars()
    {
        $team = Team::create(['name' => 'team/fourth']);
        $this->assertEquals(0, Team::where('team', 'role/fourth')->count());
    }

    public function testDelete()
    {
        Team::where('name', 'team-fourth')->delete();

        $this->assertEquals(0, Team::where('name', 'team-fourth')->count());
    }

    /**
     * ATTACHING TO USERS
     */

    public function testAttachingToUsersSingle()
    {
        $user = User::first();

        $user->attachTeam('team-first');

        $this->assertEquals(1, $user->teams()->where('name', 'team-first')->count());
    }

    public function testAttachingToUsersMultiple()
    {
        $user = User::first();

        $user->attachTeam('team-second,team-third');

        $this->assertEquals(3, $user->teams()->count());
    }

    public function testDetachingFromUsersSingle()
    {
        $user = User::first();

        $user->detachTeam('team-first');

        $this->assertEquals(0, $user->teams()->where('name', 'team-first')->count());
    }

    public function testDetachingFromUsersMultiple()
    {
        $user = User::first();

        $user->detachTeam('team-second,team-third');

        $this->assertEquals(0, $user->teams()->count());
    }

    public function testHasRoleSingleUserSingleTeam()
    {
        $user = User::first();
        $user->attachTeam('team-first');

        $this->assertTrue($user->hasTeam('team-first'));
    }

    public function testHasRoleSingleUserAllTeams()
    {
        $user = User::first();
        $user->attachTeams('team-second,team-third');
        $this->assertTrue($user->hasTeams('team-first,team-second,team-third', true));
        $this->assertTrue($user->hasTeams(['team-first'], ['team-second'], ['team-third'], true));
    }

    public function testHasRoleSingleUserOneOfTeams()
    {
        $user = User::first();
        $this->assertTrue($user->hasTeams('team-first,team-second,team-third', false));
        $this->assertTrue($user->hasTeams(['team-first'], ['team-second'], ['team-third'], false));
    }

    public function testUserHasTeamAliases()
    {
        $user = User::first();
        $this->assertTrue($user->hasTeams('team-first'));
        $this->assertTrue($user->hasTeams(['team-first']));
        $this->assertTrue($user->memberOf('team-first'));

        // @todo Tests for facade class
    }

    /**
     * ATTACHING TO ROLES
     */

    public function testAttachingToRolesSingle()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertNotNull($role);

        $role->attachTeam('team-first');

        $this->assertEquals(1, $role->teams()->where('name', 'team-first')->count());
    }

    public function testAttachingToRolesMultiple()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertNotNull($role);

        $role->attachTeam('team-second,team-third');

        $this->assertEquals(3, $role->teams()->count());
    }

    public function testDetachingFromRolesSingle()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertNotNull($role);

        $role->detachTeam('team-first');

        $this->assertEquals(0, $role->teams()->where('name', 'team-first')->count());
    }

    public function testDetachingFromRolesMultiple()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertNotNull($role);

        $role->detachTeam('team-second,team-third');

        $this->assertEquals(0, $role->teams()->count());
    }

    public function testHasRoleSingleRoleSingleTeam()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertNotNull($role);

        $role->attachTeam('team-first');

        $this->assertTrue($role->hasTeam('team-first'));
    }

    public function testHasRoleSingleRoleAllTeams()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertNotNull($role);

        $role->attachTeams('team-second,team-third');
        $this->assertTrue($role->hasTeams('team-first,team-second,team-third', true));
        $this->assertTrue($role->hasTeams(['team-first'], ['team-second'], ['team-third'], true));
    }

    public function testHasRoleSingleRoleOneOfTeams()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertNotNull($role);

        $this->assertTrue($role->hasTeams('team-first,team-second,team-third', false));
        $this->assertTrue($role->hasTeams(['team-first'], ['team-second'], ['team-third'], false));
    }

    public function testRoleHasTeamAliases()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertNotNull($role);

        $this->assertTrue($role->hasTeams('team-first'));
        $this->assertTrue($role->hasTeams(['team-first']));
        $this->assertTrue($role->memberOf('team-first'));

        // @todo Tests for facade class
    }
}
