<?php

namespace Vegvisir\TrustNoSql\Contracts;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
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
