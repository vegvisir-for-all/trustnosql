<?php

namespace Vegvisir\TrustNoSql\Checkers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Support\Facades\Config;
use Jenssegers\Mongodb\Eloquent\Model;
use Vegvisir\TrustNoSql\Checkers\Permission\PermissionChecker;
use Vegvisir\TrustNoSql\Checkers\Role\RoleChecker;
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
        $this->model($model);
    }

    /**
     * Gets the right checker basing on the type of $this->model.
     *
     * @return \Vegvisir\TrustNoSql\Checkers\Permission\PermissionChecker|\Vegvisir\TrustNoSql\Checkers\Role\RoleChecker|\Vegvisir\TrustNoSql\Checkers\User\UserChecker
     */
    public function getChecker()
    {

        if(is_a($this->model, static::PERMISSION_MODEL, true)) {
            return $this->getPermissionChecker();
        }

        if(is_a($this->model, static::ROLE_MODEL, true)) {
            return $this->getRoleChecker();
        }

        if(is_a($this->model, Config::get('laratrust.user_models.user'), true)) {
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
        return (new PermissionChecker($model));
    }

    /**
     * Gets the role checker.
     *
     * @return \Vegvisir\TrustNoSql\Checkers\Role\RoleChecker
     */
    protected function getRoleChecker()
    {
        return (new RoleChecker($model));
    }

    /**
     * Gets the user checker.
     *
     * @return \Vegvisir\TrustNoSql\Checkers\User\UserChecker
     */
    protected function getUserChecker()
    {
        return (new UserChecker($model));
    }

}
