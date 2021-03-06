<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests\Infrastructure\Observers;

use Illuminate\Support\Facades\Cache;

class PermissionObserver extends BaseObserver
{
    public static function rolesAttached()
    {
        Cache::put('permissions-roles-attached-event', true, 999999);
    }

    public static function rolesDetached()
    {
        Cache::put('permissions-roles-detached-event', true, 999999);
    }

    public static function usersAttached()
    {
        Cache::put('permissions-users-attached-event', true, 999999);
    }

    public static function usersDetached()
    {
        Cache::put('permissions-users-detached-event', true, 999999);
    }
}
