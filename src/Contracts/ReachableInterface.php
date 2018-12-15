<?php

namespace Vegvisir\TrustNoSql\Contracts;

interface ReachebleInterface
{

    /**
     * Override function to make more complex reachabilty request. Default true
     *
     * @param $user
     * @return bool Default true (thanks to ReachableTrait)
     */
    public function isReachableBy($user); // TODO: Make a proper type comparison

}
