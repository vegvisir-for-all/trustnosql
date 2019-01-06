<?php

namespace Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables;

use Vegvisir\TrustNoSql\Models\Grabbable as TrustNoSqlGrabbable;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class ModeGrabbable extends TrustNoSqlGrabbable
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->setGrababilityMode(self::MODE_GRABBABLE);
    }

    public function grabbableBy($user)
    {
        parent::grabbableBy($user);
        $grabber = User::where(1)->orderBy('_id', 'desc')->first();
        return $user->_id === $grabber->_id;
    }
}
