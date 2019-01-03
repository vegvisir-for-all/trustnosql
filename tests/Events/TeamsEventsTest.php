<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests\Events;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class TeamsEventsTest extends Events
{
    protected static function setConfigToTrue()
    {
        Config::set('trustnosql.teams.use_teams', true);
    }

    public function testRolesAttachedEvent()
    {
        $key = 'teams-roles-attached-event';

        Cache::put($key, false, 999999);

        $role = Role::create(['name' => 'admin']);
        $team = Team::create(['name' => 'test']);

        $team->attachRole($role->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testRolesDetachedEvent()
    {
        $key = 'teams-roles-detached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $team = Team::where('name', 'test')->first();

        $team->detachRole($role->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testUsersAttachedEvent()
    {
        $key = 'teams-users-attached-event';

        Cache::put($key, false, 999999);

        $team = Team::where('name', 'test')->first();
        $user = User::where(1)->first();

        $team->attachUser($user->email);

        $this->assertTrue(Cache::pull($key));
    }

    public function testUsersDetachedEvent()
    {
        $key = 'teams-users-detached-event';

        Cache::put($key, false, 999999);

        $team = Team::where('name', 'test')->first();
        $user = User::where(1)->first();

        $team->detachUser($user->email);

        $this->assertTrue(Cache::pull($key));
    }
}
