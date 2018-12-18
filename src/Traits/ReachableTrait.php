<?php

namespace Vegvisir\TrustNoSql\Traits;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
trait ReachableTrait
{

    /**
     * Override function to make more complex reachabilty request. Default true
     *
     * @param $user
     * @return bool Default true (thanks to AccessibleTrait)
     */
    public function isReachableBy($user)
    {
        return true;
    }

}
