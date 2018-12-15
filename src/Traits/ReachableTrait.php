<?php

namespace Vegvisir\TrustNoSql\Traits;

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
