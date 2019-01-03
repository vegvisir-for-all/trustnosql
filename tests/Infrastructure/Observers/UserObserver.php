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

class UserObserver extends BaseObserver
{
    public static function permissionsAttached()
    {
        Cache::put('users-permissions-attached-event', true, 999999);
    }

    public static function permissionsDetached()
    {
        Cache::put('users-permissions-detached-event', true, 999999);
    }

    public static function rolesAttached()
    {
        Cache::put('users-roles-attached-event', true, 999999);
    }

    public static function rolesDetached()
    {
        Cache::put('users-roles-detached-event', true, 999999);
    }

    public static function teamsAttached()
    {
        Cache::put('users-teams-attached-event', true, 999999);
    }

    public static function teamsDetached()
    {
        Cache::put('users-teams-detached-event', true, 999999);
    }
}
