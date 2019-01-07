<?php

namespace Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables;

use Vegvisir\TrustNoSql\Models\Grabbable as TrustNoSqlGrabbable;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class ModeBoth extends TrustNoSqlGrabbable
{
    /**
     * Fillable properties of the model.
     *
     * @var array
     */
    protected $fillable = ['name', 'owner_ids'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setGrababilityMode(self::MODE_BOTH);
    }

    public function grabbableBy($user)
    {
        $grabber = User::where(1)->first();
        return $user->_id === $grabber->_id;
    }
}
