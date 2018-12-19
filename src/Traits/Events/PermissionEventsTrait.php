<?php

namespace Vegvisir\TrustNoSql\Traits\Events;

use Vegvisir\TrustNoSql\Traits\Events\ModelEventsTrait;

trait PermissionEventsTrait
{

    use ModelEventsTrait;

    protected static $trustNoSqlObservables = [
        'rolesAttached',
        'rolesDetached',
        'usersAttached',
        'usersDetached'
    ];

}
