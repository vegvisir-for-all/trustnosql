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
    protected function permissionsAttached()
    {
        Cache::put('permissions-attached-event', true, 999999);
    }
}
