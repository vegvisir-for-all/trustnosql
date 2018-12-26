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
use Vegvisir\TrustNoSql\Tests\TestCase;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class TeamsTest extends TestCase
{

    protected static function setConfigToTrue()
    {
        Config::set('trustnosql.teams.use_teams', true);
    }

    protected static function setConfigToFalse()
    {
        Config::set('trustnosql.teams.use_teams', false);
    }

    public function testTeamFunctionalityOn()
    {
        self::setConfigToTrue();
        $this->assertTrue(Helper::isTeamFunctionalityOn());
    }

    public function testTeamFunctionalityOff()
    {
        self::setConfigToFalse();
        $this->assertFalse(Helper::isTeamFunctionalityOn());
    }

    public function testCreate()
    {
        self::setConfigToTrue();

        $teamsArray = [
            [
                'name' => 'vegvisir',
                'display_name' => 'Fundacja Vegvisir'
            ],
            [
                'name' => 'vegdev',
                'display_name' => 'Vegvisir Sp. z o.o.'
            ],
            [
                'name' => 'sigrun',
                'display_name' => 'Sigrun Sp. z o.o.'
            ]
        ];

        foreach($teamsArray as $teamData) {

            $team = Team::create($teamData);

            $this->assertEquals($teamData['name'], $team->name);
            $this->assertEquals($teamData['display_name'], $team->display_name);
        }
    }

    public function testRejectCreate()
    {
        $team = Team::create([
            'name' => 'vegvisir'
        ]);

        $this->assertNull($team);

        self::setConfigToFalse();

        $team = Team::create([
            'name' => 'vegvisir-for-all'
        ]);

        $this->assertNull($team);
    }

    public function testDelete()
    {
        self::setConfigToTrue();

        Team::where('name', 'sigrun')->delete();
        $this->assertEquals(0, Team::where('name', 'sigrun')->count());
    }

    public function testAttachingToRoles()
    {
        self::setConfigToTrue();

        Team::create(['name' => 'sigrun']);
    }

    public function testDetachingFromRoles()
    {

    }

    public function testAttachingToUsers()
    {

    }

    public function testDetachingFromUsers()
    {

    }

}
