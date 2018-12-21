<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * (c) Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 *
 * This source file is subject to the GPL-3.0-or-later license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vegvisir\TrustNoSql\Traits;

trait GrabbableTrait
{
    /**
     * If false, then grabbableBy method was overriden.
     *
     * @var bool
     */
    protected $grababilityLock = false;

    /**
     * Grabability mode.
     *
     * @var int
     */
    protected $grababilityMode = 3;

    /**
     * Set grabability mode.
     *
     * @param int $code
     */
    public function setGrababilityMode($code)
    {
        $this->grababilityMode = $code;
    }

    /**
     * Function called by TrustNoSql on object to establish grabability rules.
     *
     * @param $user
     *
     * @return bool Default true
     */
    final public function canBeGrabbedBy($user)
    {
        switch ($this->grababilityMode) {
            case static::MODE_ONLY_EXPLICIT:
                return $this->explicitelyGrabbedBy($user);

                break;
            case static::MODE_ONLY_GRABBABLE:
                return $this->grababilityLock ? true : $this->grabbableBy($user);

                break;
            case static::MODE_BOTH:
                return ($this->explicitelyGrabbedBy($user)) && ($this->grababilityLock ? true : $this->grabbableBy($user));

                break;
            case static::MODE_EITHER:
                return ($this->explicitelyGrabbedBy($user)) || ($this->grababilityLock ? true : $this->grabbableBy($user));

                break;
            default:
                return true;

                break;
        }
    }

    /**
     * Checks whether $user->id is in the grabber_ids or owner_ids field of $this.
     *
     * @param User $user
     *
     * @return bool
     */
    public function explicitelyGrabbedBy($user)
    {
        if (!isset($this->grabber_ids) && !isset($this->owner_ids)) {
            return true;
        }

        return \in_array($user->id, $this->grabber_ids, true) || \in_array($user->id, $this->owner_ids, true);
    }

    /**
     * Function to be overriden in the model to establish grabability rules.
     *
     * @param User $user
     *
     * @return bool
     */
    public function grabbableBy($user)
    {
        $this->grababilityLock = true;
    }
}
