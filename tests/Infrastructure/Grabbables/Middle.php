<?php

namespace Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables;

use Vegvisir\TrustNoSql\Models\Grabbable as TrustNoSqlGrabbable;

class Middle extends TrustNoSqlGrabbable
{
    /**
     * Fillable properties of the model.
     *
     * @var array
     */
    protected $fillable = ['name'];
}