<?php

namespace Vegvisir\TrustNoSql\Traits;

trait AccessibleTrait
{

    /**
     * Override function to make more complex accessibility request. Default
     *
     * @param $user
     * @return bool Default true (thanks to AccessibleTrait)
     */
    public function isAccessibleFor($user)
    {
        return true;
    }

}
