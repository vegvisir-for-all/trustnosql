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

    protected $grababilityLock = false;

    protected $grababilityMode = 3;

    public function setGrababilityMode($code)
    {
        $this->grababilityMode = $code;
    }

    /**
     * Override function to make more complex reachabilty request. Default true
     *
     * @param $user
     * @return bool Default true (thanks to AccessibleTrait)
     */
    public function canBeGrabbedBy($user)
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

    public function explicitelyGrabbedBy($user)
    {
        if(!isset($this->grabber_ids) && !isset($this->owner_ids)) {
            return true;
        }

        return (in_array($user->id, $this->grabber_ids) || in_array($user->id, $this->owner_ids));
    }

    public function grabbableBy($user)
    {
        $this->grababilityLock = true;
    }

}
