<?php

namespace Vegvisir\TrustNoSql\Traits\Events;

use Vegvisir\TrustNoSql\Traits\Events\ModelEventsTrait;

trait UserEventsTrait
{

    protected $observables = [
        'rolesAttached'
    ];

}
