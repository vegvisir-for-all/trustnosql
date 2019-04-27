<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Traits\Events;

trait UserEventsTrait
{
    use ModelEventsTrait;

    /**
     * TrustNoSql observable event names for user.
     *
     * @var array
     */
    protected static $trustNoSqlObservables = [
        'rolesAttached',
        'rolesDetached',
        'permissionsAttached',
        'permissionsDetached',
        'teamsAttached',
        'teamsDetached',
    ];
}
