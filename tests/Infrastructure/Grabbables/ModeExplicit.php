<?php

namespace Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables;

use Vegvisir\TrustNoSql\Models\Grabbable as TrustNoSqlGrabbable;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class ModeExplicit extends TrustNoSqlGrabbable
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $ownerIds = [
            User::where(1)->first()->id
        ];

        $this->owner_ids = $ownerIds;

        $this->setGrababilityMode(self::MODE_EXPLICIT);
    }
}
