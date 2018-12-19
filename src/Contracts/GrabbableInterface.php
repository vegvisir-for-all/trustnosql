<?php

namespace Vegvisir\TrustNoSql\Contracts;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
interface GrabbableInterface
{

    const MODE_ONLY_EXPLICIT = 1;

    const MODE_ONLY_GRABBALE = 2;

    const MODE_BOTH = 3;

    const MODE_EITHER = 4;

    const MODE_NONE = 5;

    /**
     * Override function to make more complex reachabilty request. Default true
     *
     * @param $user
     * @return bool Default true (thanks to ReachableTrait)
     */
    function canBeGrabbedBy($user); // TODO: Make a proper type comparison

    /**
     * Checks whether $user->id is in the grabber_ids field of $this
     */
    function explicitelyGrabbedBy($user);

    function grabbableBy($user);

}
