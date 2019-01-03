<?php

namespace Vegvisir\TrustNoSql\Traits\Aliases;

trait ModelAliasesTrait
{
    public function isA($roleName)
    {
        return $this->hasEntities('role', $roleName, false);
    }

    public function isAn($roleName)
    {
        return $this->hasEntities('role', $roleName, false);
    }

    public function memberOf($teamName)
    {
        return $this->hasEntities('team', $teamName, false);
    }
}
