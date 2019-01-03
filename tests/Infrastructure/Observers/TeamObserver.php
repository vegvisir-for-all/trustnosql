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

class TeamObserver extends BaseObserver
{

    protected function rolesAttached()
    {
        Cache::put('teams-roles-attached-event', true, 999999);
    }

    protected function rolesDetached()
    {
        Cache::put('teams-roles-detached-event', true, 999999);
    }

    protected function usersAttached()
    {
        Cache::put('teams-users-attached-event', true, 999999);
    }

    protected function usersDetached()
    {
        Cache::put('teams-users-detached-event', true, 999999);
    }
}
