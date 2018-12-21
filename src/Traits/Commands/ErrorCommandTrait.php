<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Traits\Commands;

trait ErrorCommandTrait
{
    /**
     * Outputs an error message, and if in development environment, add exception message.
     *
     * @param string $message      Message for the end-user
     * @param string $exceptionMsg (Optional) Thrown exception message (for development purposes)
     */
    protected function outputError($message, $exceptionMsg = null)
    {
        //if(env('APP_ENV') == 'local' && env('APP_DEBUG')) {
        if (null !== $exceptionMsg) {
            $message .= ' ('.$exceptionMsg.')';
        }
        //}

        return $this->error($message);
    }

    /**
     * Outputs a does-not-exist error message.
     *
     * @param string $what Resource type
     * @param string $name Resource name
     */
    protected function doesNotExist($what, $name)
    {
        return $this->outputError("The ${what} '${name}' does not exist. Sorry :(");
    }

    /**
     * Outputs an already-exists error message.
     *
     * @param string $what Resource type
     * @param string $name Resource name
     */
    protected function alreadyExists($what, $name)
    {
        return $this->outputError("The ${what} '${name}' already exists. Sorry :(");
    }

    /**
     * Outputs a non-attached error message.
     *
     * @param string $what       Resource being attached type
     * @param string $name       Resource being attached name
     * @param string $whatTo     Resource being attached to type
     * @param string $whatToName Resource being attached to name
     * @param string $teamName   (Optional) Team name
     * @param mixed  $whatName
     */
    protected function notAttached($what, $whatName, $whatTo, $whatToName, $teamName = null)
    {
        $teamInfo = '';

        if (null !== $teamName) {
            $teamInfo = " within '${teamName}' team";
        }

        return $this->outputError("The ${what} '${whatName}' is not attached to ${whatTo} '${whatToName}'${teamInfo}. Sorry :(");
    }

    /**
     * Outputs an already-attached error.
     *
     * @param string $what       Resource being attached type
     * @param string $name       Resource being attached name
     * @param string $whatTo     Resource being attached to type
     * @param string $whatToName Resource being attached to name
     * @param string $teamName   (Optional) Team name
     * @param mixed  $whatName
     */
    protected function alreadyAttached($what, $whatName, $whatTo, $whatToName, $teamName = null)
    {
        $teamInfo = '';

        if (null !== $teamName) {
            $teamInfo = " on '${teamName}' team";
        }

        return $this->outputError("The ${what} '${whatName}' is already attached to ${whatTo} '${whatToName}'${teamInfo}. Sorry :(");
    }

    /**
     * Outputs a creation error message.
     *
     * @param string $what         Resource being created type
     * @param string $whatName     Resource being created name
     * @param string $exceptionMsg (Optional) Thrown exception message (for development purposes)
     */
    protected function errorCreating($what, $whatName, $exceptionMsg = null)
    {
        return $this->outputError("Error creating ${what} '${whatName}'. Sorry :(", $exceptionMsg);
    }

    /**
     * Outputs a deletion error message.
     *
     * @param string $what         Resource being created type
     * @param string $whatName     Resource being created name
     * @param string $exceptionMsg (Optional) Thrown exception message (for development purposes)
     */
    protected function errorDeleting($what, $whatName, $exceptionMsg = null)
    {
        return $this->outputError("Error deleting ${what} '${whatName}'. Sorry :(", $exceptionMsg);
    }

    /**
     * Outputs an attaching error message.
     *
     * @param string $what         Resource being attached type
     * @param string $name         Resource being attached name
     * @param string $whatTo       Resource being attached to type
     * @param string $whatToName   Resource being attached to name
     * @param string $teamName     (Optional) Team name
     * @param string $exceptionMsg (Optional) Thrown exception message (for development purposes)
     * @param mixed  $whatName
     */
    protected function errorAttaching($what, $whatName, $whatTo, $whatToName, $teamName = null, $exceptionMsg = null)
    {
        $teamInfo = '';

        if (null !== $teamName) {
            $teamInfo = " within '${teamName}' team";
        }

        return $this->outputError("Error attaching ${what} '${whatName}' to ${whatTo} '${whatToName}'${teamInfo}. Sorry :(", $exceptionMsg);
    }

    /**
     * Outputs a detaching error message.
     *
     * @param string $what         Resource being attached type
     * @param string $name         Resource being attached name
     * @param string $whatTo       Resource being attached to type
     * @param string $whatToName   Resource being attached to name
     * @param string $teamName     (Optional) Team name
     * @param string $exceptionMsg (Optional) Thrown exception message (for development purposes)
     * @param mixed  $whatName
     */
    protected function errorDetaching($what, $whatName, $whatTo, $whatToName, $teamName = null, $exceptionMsg = nul)
    {
        $teamInfo = '';

        if (null !== $teamName) {
            $teamInfo = " within '${teamName}' team";
        }

        return $this->outputError("Error detaching ${what} '${whatName}' from ${whatTo} '${whatToName}'${teamInfo}. Sorry :(", $exceptionMsg);
    }

    /**
     * Outputs a no-team-functionality error message.
     */
    protected function noTeamFunctionality()
    {
        return $this->outputError('Team functionality is off (and it should be). Set `teams.use_teams` in `config/trustnosql.php` to `true` to turn it on');
    }

    /**
     * Outputs an error-team-functionality error message.
     */
    protected function erroTeamFunctionality()
    {
        return $this->outputError('Team functionality is on (and it shouldn\'t be). Set `teams.use_teams` in `config/trustnosql.php` to `false` to turn it off');
    }
}
