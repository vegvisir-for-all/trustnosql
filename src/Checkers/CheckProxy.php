<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Checkers;

use Illuminate\Support\Facades\Config;
use Jenssegers\Mongodb\Eloquent\Model;
use Vegvisir\TrustNoSql\Checkers\Permission\PermissionChecker;
use Vegvisir\TrustNoSql\Checkers\Role\RoleChecker;
use Vegvisir\TrustNoSql\Checkers\Team\TeamChecker;
use Vegvisir\TrustNoSql\Checkers\User\UserChecker;

class CheckProxy
{
    /**
     * Permission model name.
     *
     * @var string
     */
    const PERMISSION_MODEL = \Vegvisir\TrustNoSql\Models\Permission::class;

    /**
     * Role model name.
     *
     * @var string
     */
    const ROLE_MODEL = \Vegvisir\TrustNoSql\Models\Role::class;

    /**
     * Team model name.
     *
     * @var string
     */
    const TEAM_MODEL = \Vegvisir\TrustNoSql\Models\Team::class;

    /**
     * Model to be checked within.
     *
     * @var Jenssegers\Mongodb\Eloquent\Model
     */
    protected $model;

    /**
     * Creates a new instance.
     *
     * @var Jenssegers\Mongodb\Eloquent\Model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Gets the right checker basing on the type of $this->model.
     *
     * @return \Vegvisir\TrustNoSql\Checkers\Permission\PermissionChecker|\Vegvisir\TrustNoSql\Checkers\Role\RoleChecker|\Vegvisir\TrustNoSql\Checkers\Role\TeamChecker|\Vegvisir\TrustNoSql\Checkers\User\UserChecker
     */
    public function getChecker()
    {
        if (is_a($this->model, static::PERMISSION_MODEL, true)) {
            return $this->getPermissionChecker();
        }

        if (is_a($this->model, static::ROLE_MODEL, true)) {
            return $this->getRoleChecker();
        }

        if (is_a($this->model, static::TEAM_MODEL, true)) {
            return $this->getTeamChecker();
        }

        if (is_a($this->model, Config::get('trustnosql.user_models.users'), true)) {
            return $this->getUserChecker();
        }
    }

    /**
     * Gets the permission checker.
     *
     * @return \Vegvisir\TrustNoSql\Checkers\Permission\PermissionChecker
     */
    protected function getPermissionChecker()
    {
        return new PermissionChecker($this->model);
    }

    /**
     * Gets the role checker.
     *
     * @return \Vegvisir\TrustNoSql\Checkers\Role\RoleChecker
     */
    protected function getRoleChecker()
    {
        return new RoleChecker($this->model);
    }

    /**
     * Gets the team checker.
     *
     * @return \Vegvisir\TrustNoSql\Checkers\Team\TeamChecker
     */
    protected function getTeamChecker()
    {
        return new TeamChecker($this->model);
    }

    /**
     * Gets the user checker.
     *
     * @return \Vegvisir\TrustNoSql\Checkers\User\UserChecker
     */
    protected function getUserChecker()
    {
        return new UserChecker($this->model);
    }
}
