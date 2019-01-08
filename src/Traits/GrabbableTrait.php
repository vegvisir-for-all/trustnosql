<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Traits;

trait GrabbableTrait
{

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
     * Gets grabability mode.
     *
     * @return int
     */
    public function getGrababilityMode()
    {
        return $this->grababilityMode;
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
            case static::MODE_EXPLICIT:
                return $this->explicitelyGrabbedBy($user);

                break;
            case static::MODE_GRABBABLE:
                return $this->grabbableBy($user);

                break;
            case static::MODE_BOTH:
                return ($this->explicitelyGrabbedBy($user)) && ($this->grabbableBy($user));

                break;
            case static::MODE_EITHER:
                return ($this->explicitelyGrabbedBy($user)) || ($this->grabbableBy($user));

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

        if (!isset($this->grabber_ids) || !is_array($this->grabber_ids)) {
            $this->grabber_ids = [];
        }

        if (!isset($this->owner_ids) || !is_array($this->owner_ids)) {
            $this->owner_ids = [];
        }

        return \in_array($user->id, $this->grabber_ids, true) || \in_array($user->id, $this->owner_ids, true);
    }
}
