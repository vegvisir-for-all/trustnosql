<?php

namespace Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables;

use Vegvisir\TrustNoSql\Models\Grabbable as TrustNoSqlGrabbable;

class RulesOverwritten extends TrustNoSqlGrabbable
{
    public function grabbableBy($user)
    {
        return true;
    }
}
