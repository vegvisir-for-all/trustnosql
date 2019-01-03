<?php

namespace Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables;

use Vegvisir\TrustNoSql\Models\Grabbable as TrustNoSqlGrabbable;

class ModeExplicit extends TrustNoSqlGrabbable
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setGrababilityMode(self::MODE_EXPLICIT);
    }
}
