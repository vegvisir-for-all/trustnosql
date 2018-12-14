<?php

namespace Vegvisir\TrustNoSql\Contracts;

interface AccessibleInterface
{

    /**
     * Override function to make more complex accessibility request. Default
     *
     * @param $user
     * @return bool Default true (thanks to AccessibleTrait)
     */
    public function isAccessibleFor($user); // TODO: Make a proper type comparison

}
