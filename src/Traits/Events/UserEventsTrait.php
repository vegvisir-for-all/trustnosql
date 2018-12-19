<?php

namespace Vegvisir\TrustNoSql\Traits\Events;

use Vegvisir\TrustNoSql\Traits\Events\ModelEventsTrait;

trait UserEventsTrait
{

    use ModelEventsTrait;

    protected static $trustNoSqlObservables = [
        'rolesAttached',
        'rolesDetached',
        'permissionsAttached',
        'permissionsDetached',
        'teamsAttached',
        'teamsDetached'
    ];

}
