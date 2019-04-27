<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql;

class TrustNoSql
{
    /**
     * Laravel application.
     *
     * @var \Illuminate\Foundation\Application
     */
    public $app;

    /**
     * Create a new confide instance.
     *
     * @param \Illuminate\Foundation\Application
     * @param mixed $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get the currently authenticated user or null.
     *
     * @return null|\Illuminate\Auth\UserInterface
     */
    public function user()
    {
        return $this->app->auth->user();
    }

    /**
     * Checks if the current user has a role by its name.
     *
     * @param string     $role       role name
     * @param null|mixed $team
     * @param mixed      $requireAll
     *
     * @return bool
     */
    public function hasRole($role, $team = null, $requireAll = false)
    {
        if ($user = $this->user()) {
            return $user->hasRole($role, $team, $requireAll);
        }

        return false;
    }

    /**
     * Check if the current user has a permission by its name.
     *
     * @param string     $permission permission string
     * @param null|mixed $team
     * @param mixed      $requireAll
     *
     * @return bool
     */
    public function can($permission, $team = null, $requireAll = false)
    {
        if ($user = $this->user()) {
            return $user->hasPermission($permission, $team, $requireAll);
        }

        return false;
    }
}
