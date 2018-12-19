<?php

namespace Vegvisir\TrustNoSql\Models;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Jenssegers\Mongodb\Eloquent\Model;
use Vegvisir\TrustNoSql\Contracts\GrabbableInterface;
use Vegvisir\TrustNoSql\Traits\GrabbableTrait;

class Grabbable extends Model implements GrabbableInterface
{

    use GrabbableTrait;

}
