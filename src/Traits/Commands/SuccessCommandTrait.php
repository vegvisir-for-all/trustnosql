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

namespace Vegvisir\TrustNoSql\Traits\Commands;

trait SuccessCommandTrait
{
    /**
     * Outputs a creation success message.
     *
     * @param string $what     Resource being created type
     * @param string $name     Resource being created name
     * @param mixed  $whatName
     */
    protected function successCreating($what, $whatName)
    {
        $this->info(\ucfirst($what)." '${whatName}' was created successfully. Glad I could help :)");
    }

    /**
     * Outputs a deletion success message.
     *
     * @param string $what     Resource being created type
     * @param string $name     Resource being created name
     * @param mixed  $whatName
     */
    protected function successDeleting($what, $whatName)
    {
        $this->info(\ucfirst($what)." '${whatName}' was deleted successfully. Glad I could help :)");
    }

    /**
     * Outputs an attaching success message.
     *
     * @param string $what       Resource being attached type
     * @param string $name       Resource being attached name
     * @param string $whatTo     Resource being attached to type
     * @param string $whatToName Resource being attached to name
     * @param string $teamName   (Optional) Team name
     * @param mixed  $whatName
     */
    protected function successAttaching($what, $whatName, $whatTo, $whatToName, $teamName = null)
    {
        $teamInfo = '';

        if (null !== $teamName) {
            $teamInfo = " on '${teamName}' team";
        }

        $this->info(\ucfirst($what)." '${whatName}' was attached successfully to ${whatTo} '${whatToName}'${teamInfo}. Glad I could help :)");
    }

    /**
     * Outputs a detaching success message.
     *
     * @param string $what       Resource being attached type
     * @param string $name       Resource being attached name
     * @param string $whatTo     Resource being attached to type
     * @param string $whatToName Resource being attached to name
     * @param string $teamName   (Optional) Team name
     * @param mixed  $whatName
     */
    protected function successDetaching($what, $whatName, $whatTo, $whatToName, $teamName = null)
    {
        $teamInfo = '';
        if (null !== $teamName) {
            $teamInfo = " on '${teamName}' team";
        }
        $this->info(\ucfirst($what)." '${whatName}' detached successfully from ${whatTo} '${whatToName}'${teamInfo}. Glad I could help :)");
    }
}
