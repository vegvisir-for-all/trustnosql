<?php

namespace Vegvisir\TrustNoSql\Traits\Events;

use Vegvisir\TrustNoSql\Traits\Events\ModelEventsTrait;

trait TeamEventsTrait
{

    use ModelEventsTrait;

    protected static $trustNoSqlObservables = [
        'rolesAttached'
    ];

}
