<?php

namespace Vegvisir\TrustNoSql\Traits\Events;

use Vegvisir\TrustNoSql\Traits\Events\ModelEventsTrait;

trait RoleEventsTrait
{

    use ModelEventsTrait;

    protected static $trustNoSqlObservables = [
        'permissionsAttached'
    ];

}
