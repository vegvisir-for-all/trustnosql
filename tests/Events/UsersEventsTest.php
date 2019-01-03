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

class UsersEventsTest extends Events
{
    protected static function setConfigToTrue()
    {
        Config::set('trustnosql.teams.use_teams', true);
    }

    public function testPermissionsAttachedEvent()
    {

        $key = 'users-permissions-attached-event';

        Cache::put($key, false, 999999);

        $user = User::where(1)->first();
        $permission = Permission::where(['name' => 'everything/do'])->first();

        $user->attachPermission($permission->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testPermissionsDetachedEvent()
    {

        $key = 'users-permissions-detached-event';

        Cache::put($key, false, 999999);

        $user = User::where(1)->first();
        $permission = Permission::where('name', 'everything/do')->first();

        $user->detachPermission($permission->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testRolesAttachedEvent()
    {

        $key = 'users-roles-attached-event';

        Cache::put($key, false, 999999);

        $role = Role::create(['name' => 'admin']);
        $user = User::where(1)->first();

        $user->attachRole($role->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testRolesDetachedEvent()
    {

        $key = 'users-roles-detached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $user = User::where(1)->first();

        $user->detachRole($role->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testTeamsAttachedEvent()
    {

        $key = 'users-teams-attached-event';

        Cache::put($key, false, 999999);

        $user = User::where(1)->first();
        $team = Team::create(['name' => 'users']);

        $user->attachTeam($team->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testTeamsDetachedEvent()
    {

        $key = 'users-teams-detached-event';

        Cache::put($key, false, 999999);

        $user = User::where(1)->first();
        $team = Team::where('name', 'users')->first();

        $user->detachTeam($team->name);

        $this->assertTrue(Cache::pull($key));
    }
}
