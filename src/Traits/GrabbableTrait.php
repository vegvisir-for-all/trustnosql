<?php

namespace Vegvisir\TrustNoSql\Traits;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
trait GrabbableTrait
{

    /**
     * If false, then grabbableBy method was overriden
     *
     * @var bool
     */
    protected $grababilityLock = false;

    /**
     * Grabability mode
     *
     * @var int
     */
    protected $grababilityMode = 3;

    /**
     * Set grabability mode
     *
     * @param int $code
     * @return void
     */
    public function setGrababilityMode($code)
    {
        $this->grababilityMode = $code;
    }

    /**
     * Function called by TrustNoSql on object to establish grabability rules.
     *
     * @param $user
     * @return bool Default true
     */
    public final function canBeGrabbedBy($user)
    {
        switch($this->grababilityMode) {
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
     * Checks whether $user->id is in the grabber_ids or owner_ids field of $this
     *
     * @param User $user
     * @return bool
     */
    public function explicitelyGrabbedBy($user)
    {
        if(!isset($this->grabber_ids) && !isset($this->owner_ids)) {
            return true;
        }

        return (in_array($user->id, $this->grabber_ids) || in_array($user->id, $this->owner_ids));
    }

    /**
     * Function to be overriden in the model to establish grabability rules.
     *
     * @param User $user
     * @return bool
     */
    public function grabbableBy($user)
    {
        $this->grababilityLock = true;
    }

}
