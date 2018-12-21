<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Vegvisir\TrustNoSql\Contracts\GrabbableInterface;
use Vegvisir\TrustNoSql\Traits\GrabbableTrait;

class Grabbable extends Model implements GrabbableInterface
{
    use GrabbableTrait;
}
