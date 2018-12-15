<?php

namespace Vegvisir\TrustNoSql\Traits\Commands;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
trait ErrorCommandTrait
{

    /**
     * Outputs a does-not-exist error message.
     *
     * @param string $what Resource type
     * @param string $name Resource name
     */
    protected function doesNotExist($what, $name)
    {
        return $this->error("The $what '$name' does not exist. Sorry :(");
    }

    /**
     * Outputs an already-exists error message.
     *
     * @param string $what Resource type
     * @param string $name Resource name
     */
    protected function alreadyExists($what, $name)
    {
        return $this->error("The $what '$name' already exists. Sorry :(");
    }

    /**
     * Outputs a non-attached error message.
     *
     * @param string $what Resource being attached type
     * @param string $name Resource being attached name
     * @param string $whatTo Resource being attached to type
     * @param string $whatToName Resource being attached to name
     * @param string $teamName (Optional) Team name
     */
    protected function notAttached($what, $whatName, $whatTo, $whatToName, $teamName = null)
    {
        $teamInfo = '';

        if ($teamName !== null) {
            $teamInfo = " within '$teamName' team";
        }

        return $this->error("The $what '$whatName' is not attached to $whatTo '$whatToName'$teamInfo. Sorry :(");
    }

    /**
     * Outputs an already-attached error.
     *
     * @param string $what Resource being attached type
     * @param string $name Resource being attached name
     * @param string $whatTo Resource being attached to type
     * @param string $whatToName Resource being attached to name
     * @param string $teamName (Optional) Team name
     */
    protected function alreadyAttached($what, $whatName, $whatTo, $whatToName, $teamName = null)
    {
        $teamInfo = '';

        if ($teamName !== null) {
            $teamInfo = " on '$teamName' team";
        }

        return $this->error("The $what '$whatName' is already attached to $whatTo '$whatToName'$teamInfo. Sorry :(");
    }

    /**
     * Outputs a creation error message.
     *
     * @param string $what Resource being created type
     * @param string $whatName Resource being created name
     */
    protected function errorCreating($what, $whatName)
    {
        return $this->error("Error creating $what '$whatName'. Sorry :(");
    }
    /**
     * Outputs a deletion error message.
     *
     * @param string $what Resource being created type
     * @param string $whatName Resource being created name
     */
    protected function errorDeleting($what, $whatName)
    {
        return $this->error("Error deleting $what '$whatName'. Sorry :(");
    }

    /**
     * Outputs an attaching error message.
     *
     * @param string $what Resource being attached type
     * @param string $name Resource being attached name
     * @param string $whatTo Resource being attached to type
     * @param string $whatToName Resource being attached to name
     * @param string $teamName (Optional) Team name
     */
    protected function errorAttaching($what, $whatName, $whatTo, $whatToName, $teamName = null)
    {
        $teamInfo = '';

        if ($teamName !== null) {
            $teamInfo = " within '$teamName' team";
        }

        return $this->error("Error attaching $what '$whatName' to $whatTo '$whatToName'$teamInfo. Sorry :(");
    }

    /**
     * Outputs a detaching error message.
     *
     * @param string $what Resource being attached type
     * @param string $name Resource being attached name
     * @param string $whatTo Resource being attached to type
     * @param string $whatToName Resource being attached to name
     * @param string $teamName (Optional) Team name
     */
    protected function errorDetaching($what, $whatName, $whatTo, $whatToName, $teamName = null)
    {
        $teamInfo = '';

        if ($teamName !== null) {
            $teamInfo = " within '$teamName' team";
        }

        return $this->error("Error detaching $what '$whatName' from $whatTo '$whatToName'$teamInfo. Sorry :(");
    }

    /**
     * Outputs a no-team-functionality error message.
     */
    protected function noTeamFunctionality()
    {
        return $this->error('Team functionality is off (and it should be). Set `teams.use_teams` in `config/trustnosql.php` to `true` to turn it on');
    }

    /**
     * Outputs an error-team-functionality error message.
     */
    protected function erroTeamFunctionality()
    {
        return $this->error('Team functionality is on (and it shouldn\'t be). Set `teams.use_teams` in `config/trustnosql.php` to `false` to turn it off');
    }

}
