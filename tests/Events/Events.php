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

use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Tests\TestCase;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;

class Events extends TestCase
{
    protected static function clearAfterModelTests()
    {
        Permission::where(1)->delete();
        Role::where(1)->delete();
        Team::where(1)->delete();
    }

    protected static function setObservers()
    {
        $observers = [
            'Permission' => \Vegvisir\TrustNoSql\Tests\Infrastructure\Observers\PermissionObserver::class,
            'Role' => \Vegvisir\TrustNoSql\Tests\Infrastructure\Observers\RoleObserver::class,
            'Team' => \Vegvisir\TrustNoSql\Tests\Infrastructure\Observers\TeamObserver::class,
            'User' => \Vegvisir\TrustNoSql\Tests\Infrastructure\Observers\UserObserver::class,
        ];

        Config::set('trustnosql.events.observers', $observers);
    }
}
