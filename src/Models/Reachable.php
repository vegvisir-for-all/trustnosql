<?php

namespace Vegvisir\TrustNoSql\Models;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Jenssegers\Mongodb\Eloquent\Model;
use Vegvisir\TrustNoSql\Contracts\ReachableInterface;
use Vegvisir\TrustNoSql\Traits\ReachableTrait;

class Reachable extends Model implements ReachableInterface
{

    use ReachableTrait;

}
