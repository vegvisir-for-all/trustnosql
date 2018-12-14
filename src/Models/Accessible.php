<?php

namespace Vegvisir\TrustNoSql\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Vegvisir\TrustNoSql\Contracts\AccessibleInterface;
use Vegvisir\TrustNoSql\Traits\AccessibleTrait;

class Accessible extends Model implements AccessibleInterface
{

    use AccessibleTrait;

}
