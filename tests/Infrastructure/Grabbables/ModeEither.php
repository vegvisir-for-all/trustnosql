<?php

namespace Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables;

use Vegvisir\TrustNoSql\Models\Grabbable as TrustNoSqlGrabbable;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class ModeEither extends TrustNoSqlGrabbable
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

        $ownerIds = [
            User::where(1)->first()->id,
            User::where(1)->orderBy('_id', 'desc')->first()->id
        ];

        $this->owner_ids = $ownerIds;

        $this->setGrababilityMode(self::MODE_EITHER);
    }

    public function grabbableBy($user)
    {
        $grabber = User::where(1)->first();
        return $user->id === $grabber->id;
    }
}
