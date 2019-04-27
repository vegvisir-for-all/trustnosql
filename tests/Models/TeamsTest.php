<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests\Models;

use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

/**
 * @internal
 * @coversNothing
 */
final class TeamsTest extends ModelsTestCase
{
    protected $teamsData = [
        [
            'name' => 'team-first',
            'display_name' => 'Team First',
            'description' => 'Team First',
        ],
        [
            'name' => 'team-second',
            'display_name' => 'Team Second',
            'description' => 'Team Second',
        ],
        [
            'name' => 'team-third',
            'display_name' => 'Team Third',
            'description' => 'Team Third',
        ],
        [
            'name' => 'team-fourth',
            'display_name' => 'Team Fourth',
            'description' => 'Team Fourth',
        ],
    ];

    public function testCount()
    {
        $this->assertSame(4, Team::count());
    }

    public function testCreatedNotNull()
    {
        foreach ($this->teamsData as $teamData) {
            $team = Team::where('name', $teamData['name'])->first();
            $this->assertNotNull($team);
        }
    }

    public function testCreatedName()
    {
        foreach ($this->teamsData as $teamData) {
            $team = Team::where('name', $teamData['name'])->first();
            $this->assertSame($teamData['name'], $team->name);
        }
    }

    public function testCreatedDisplayName()
    {
        foreach ($this->teamsData as $teamData) {
            $team = Team::where('name', $teamData['name'])->first();
            $this->assertSame($teamData['display_name'], $team->display_name);
        }
    }

    // public function testRejectCreateExists()
    // {
    //     try {
    //         $team = Team::create(['name' => 'team-fourth']);
    //     } catch (\Exception $e) {
    //     }
    //     $this->assertEquals(1, Team::where('name', 'team-fourth')->count());
    // }

    // public function testRejectCreateIllegalChars()
    // {
    //     try {
    //         $team = Team::create(['name' => 'team/fourth']);
    //     } catch (\Exception $e) {
    //     }
    //     $this->assertEquals(0, Team::where('team', 'role/fourth')->count());
    // }

    public function testDelete()
    {
        Team::where('name', 'team-fourth')->delete();
        $this->assertSame(0, Team::where('name', 'team-fourth')->count());
    }

    /**
     * ATTACHING TO USERS.
     */
    public function testAttachingToUsersSingle()
    {
        $user = User::first();
        $user->attachTeam('team-first');
        $this->assertSame(1, $user->teams()->where('name', 'team-first')->count());
    }

    public function testAttachingToUsersMultiple()
    {
        $user = User::first();
        $user->attachTeam('team-second,team-third');
        $this->assertSame(3, $user->teams()->count());
    }

    public function testDetachingFromUsersSingle()
    {
        $user = User::first();
        $user->detachTeam('team-first');
        $this->assertSame(0, $user->teams()->where('name', 'team-first')->count());
    }

    public function testDetachingFromUsersMultiple()
    {
        $user = User::first();
        $user->detachTeam('team-second,team-third');
        $this->assertSame(0, $user->teams()->count());
    }

    public function testHasRoleSingleUserSingleTeam()
    {
        $user = User::first();
        $user->attachTeam('team-first');
        $this->assertTrue($user->hasTeam('team-first'));
    }

    public function testHasRoleSingleUserAllTeamsString()
    {
        $user = User::first();
        $user->attachTeams('team-second,team-third');
        $this->assertTrue($user->hasTeams('team-first,team-second,team-third', true));
    }

    public function testHasRoleSingleUserAllTeamsArray()
    {
        $user = User::first();
        $this->assertTrue($user->hasTeams(['team-first', 'team-second', 'team-third'], true));
    }

    public function testHasRoleSingleUserOneOfTeamsString()
    {
        $user = User::first();
        $this->assertTrue($user->hasTeams('team-first,team-second,team-third', false));
    }

    public function testHasRoleSingleUserOneOfTeamsArray()
    {
        $user = User::first();
        $this->assertTrue($user->hasTeams(['team-first', 'team-second', 'team-third'], false));
    }

    public function testUserHasTeamAliasesHasTeamsString()
    {
        $user = User::first();
        $this->assertTrue($user->hasTeams('team-first'));
    }

    public function testUserHasTeamAliasesHasTeamsArray()
    {
        $user = User::first();
        $this->assertTrue($user->hasTeams(['team-first']));
    }

    public function testUserHasTeamAliasesHasTeamsMemberOf()
    {
        $user = User::first();
        $this->assertTrue($user->memberOf('team-first'));
    }

    /**
     * ATTACHING TO ROLES.
     */
    public function testAttachingToRolesSingle()
    {
        $role = Role::where('name', 'role-first')->first();
        $role->attachTeam('team-first');
        $this->assertSame(1, $role->teams()->where('name', 'team-first')->count());
    }

    public function testAttachingToRolesMultiple()
    {
        $role = Role::where('name', 'role-first')->first();
        $role->attachTeam('team-second,team-third');
        $this->assertSame(3, $role->teams()->count());
    }

    public function testDetachingFromRolesSingle()
    {
        $role = Role::where('name', 'role-first')->first();
        $role->detachTeam('team-first');
        $this->assertSame(0, $role->teams()->where('name', 'team-first')->count());
    }

    public function testDetachingFromRolesMultiple()
    {
        $role = Role::where('name', 'role-first')->first();
        $role->detachTeam('team-second,team-third');
        $this->assertSame(0, $role->teams()->count());
    }

    public function testHasRoleSingleRoleSingleTeam()
    {
        $role = Role::where('name', 'role-first')->first();
        $role->attachTeam('team-first');
        $this->assertTrue($role->hasTeam('team-first'));
    }

    public function testHasRoleSingleRoleAllTeamsString()
    {
        $role = Role::where('name', 'role-first')->first();
        $role->attachTeams('team-second,team-third');
        $this->assertTrue($role->hasTeams('team-first,team-second,team-third', true));
    }

    public function testHasRoleSingleRoleAllTeamsArray()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertTrue($role->hasTeams(['team-first', 'team-second', 'team-third'], true));
    }

    public function testHasRoleSingleRoleOneOfTeamsString()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertTrue($role->hasTeams('team-first,team-second,team-third', false));
    }

    public function testHasRoleSingleRoleOneOfTeamsArray()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertTrue($role->hasTeams(['team-first', 'team-second', 'team-third'], false));
    }

    public function testRoleHasTeamAliasesHasTeamsString()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertTrue($role->hasTeams('team-first'));
    }

    public function testRoleHasTeamAliasesHasTeamsArray()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertTrue($role->hasTeams(['team-first']));
    }

    public function testRoleHasTeamAliasesMemberOf()
    {
        $role = Role::where('name', 'role-first')->first();
        $this->assertTrue($role->memberOf('team-first'));
    }
}
