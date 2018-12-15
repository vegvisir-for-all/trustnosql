<?php

namespace Vegvisir\TrustNoSql\Traits\Commands;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
trait SuccessCommandTrait
{

    /**
     * Outputs a creation success message.
     *
     * @param string $what Resource being created type
     * @param string $name Resource being created name
     */
    protected function successCreating($what, $whatName)
    {
        $this->info(\ucfirst($what)." '$whatName' was created successfully. Glad I could help :)");
    }

    /**
     * Outputs a deletion success message.
     *
     * @param string $what Resource being created type
     * @param string $name Resource being created name
     */
    protected function successDeleting($what, $whatName)
    {
        $this->info(\ucfirst($what)." '$whatName' was deleted successfully. Glad I could help :)");
    }

    /**
     * Outputs an attaching success message.
     *
     * @param string $what Resource being attached type
     * @param string $name Resource being attached name
     * @param string $whatTo Resource being attached to type
     * @param string $whatToName Resource being attached to name
     * @param string $teamName (Optional) Team name
     */
    protected function successAttaching($what, $whatName, $whatTo, $whatToName, $teamName = null)
    {
        $teamInfo = '';

        if ($teamName !== null) {
            $teamInfo = " on '$teamName' team";
        }

        $this->info(\ucfirst($what)." '$whatName' was attached successfully to $whatTo '$whatToName'$teamInfo. Glad I could help :)");
    }

    /**
     * Outputs a detaching success message.
     *
     * @param string $what Resource being attached type
     * @param string $name Resource being attached name
     * @param string $whatTo Resource being attached to type
     * @param string $whatToName Resource being attached to name
     * @param string $teamName (Optional) Team name
     */
    protected function successDetaching($what, $whatName, $whatTo, $whatToName, $teamName = null)
    {
        $teamInfo = '';
        if ($teamName !== null) {
            $teamInfo = " on '$teamName' team";
        }
        $this->info(\ucfirst($what)." '$whatName' detached successfully from $whatTo '$whatToName'$teamInfo. Glad I could help :)");
    }
}
