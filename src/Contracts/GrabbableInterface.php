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

    /**
     * Grabability can be checked only by grabber_ids or owner_ids on model
     *
     * @var int
     */
    const MODE_ONLY_EXPLICIT = 1;

    /**
     * Grabability can be checked only by grabbableBy($user) method
     *
     * @var int
     */
    const MODE_ONLY_GRABBALE = 2;

    /**
     * Grabability must be checked by explicit DB field AND by grabbableBy($user)
     * method. Only when both methods return true, object can be grabbed.
     *
     * @var int
     */
    const MODE_BOTH = 3;

    /**
     * Grabability must be checked by explicit DB field OR by grabbableBy($user)
     * method. When only one method returns true, object can be grabbed.
     *
     * @var int
     */
    const MODE_EITHER = 4;

    /**
     * Don't check grabability
     *
     * @var int
     */
    const MODE_NONE = 5;

    /**
     * Function called by TrustNoSql on object to establish grabability rules.
     *
     * @param $user
     * @return bool
     */
    function canBeGrabbedBy($user); // TODO: Make a proper type comparison

    /**
     * Checks whether $user->id is in the grabber_ids or owner_ids field of $this
     *
     * @param User $user
     * @return bool
     */
    function explicitelyGrabbedBy($user);

    /**
     * Function to be overriden in the model to establish grabability rules.
     *
     * @param User $user
     * @return bool
     */
    function grabbableBy($user);

}
