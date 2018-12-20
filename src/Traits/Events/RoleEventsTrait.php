<?php

namespace Vegvisir\TrustNoSql\Traits\Events;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Traits\Events\ModelEventsTrait;

trait RoleEventsTrait
{

    use ModelEventsTrait;

    /**
     * TrustNoSql observable event names for role
     *
     * @var array
     */
    protected static $trustNoSqlObservables = [
        'permissionsAttached',
        'permissionsDetached',
        'teamsAttached',
        'teamsDetached',
        'usersAttached',
        'usersDetached'
    ];

}
