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

class RolesEventsTest extends Events
{
    protected static function setConfigToTrue()
    {
        Config::set('trustnosql.teams.use_teams', true);
    }

    public function testPermissionsAttachedEvent()
    {
        $key = 'roles-permissions-attached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $permission = Permission::where(['name' => 'everything/do'])->first();

        $role->attachPermission($permission->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testPermissionsDetachedEvent()
    {
        $key = 'roles-permissions-detached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $permission = Permission::where('name', 'everything/do')->first();

        $role->detachPermission($permission->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testTeamsAttachedEvent()
    {
        $key = 'roles-teams-attached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $team = Team::create(['name' => 'roles']);

        $role->attachTeam($team->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testTeamsDetachedEvent()
    {
        $key = 'roles-teams-detached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $team = Team::where('name', 'roles')->first();

        $role->detachTeam($team->name);

        $this->assertTrue(Cache::pull($key));
    }

    public function testUsersAttachedEvent()
    {
        $key = 'roles-users-attached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $user = User::where(1)->first();

        $role->attachUser($user->email);

        $this->assertTrue(Cache::pull($key));
    }

    public function testUsersDetachedEvent()
    {
        $key = 'roles-users-detached-event';

        Cache::put($key, false, 999999);

        $role = Role::where('name', 'admin')->first();
        $user = User::where(1)->first();

        $role->detachUser($user->email);

        $this->assertTrue(Cache::pull($key));
    }
}
