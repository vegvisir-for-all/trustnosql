<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests\Infrastructure\Observers;

use Illuminate\Support\Facades\Cache;

class RoleObserver extends BaseObserver
{
    public static function permissionsAttached()
    {
        Cache::put('roles-permissions-attached-event', true, 999999);
    }

    public static function permissionsDetached()
    {
        Cache::put('roles-permissions-detached-event', true, 999999);
    }

    public static function teamsAttached()
    {
        Cache::put('roles-teams-attached-event', true, 999999);
    }

    public static function teamsDetached()
    {
        Cache::put('roles-teams-detached-event', true, 999999);
    }

    public static function usersAttached()
    {
        Cache::put('roles-users-attached-event', true, 999999);
    }

    public static function usersDetached()
    {
        Cache::put('roles-users-detached-event', true, 999999);
    }
}
