<?php

namespace Vegvisir\TrustNoSql\Checkers;

use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Checkers\Permission\PermissionChecker;
use Vegvisir\TrustNoSql\Checkers\Role\RoleChecker;
use Vegvisir\TrustNoSql\Checkers\User\UserChecker;

class CheckManager
{

    const PERMISSION_MODEL = \Vegvisir\TrustNoSql\Models\Permission::class;
    const ROLE_MODEL = \Vegvisir\TrustNoSql\Models\Role::class;

    protected $model;

    public function __construct($model)
    {
        $this->model($model);
    }

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

    protected function getPermissionChecker()
    {
        return (new PermissionChecker($model));
    }

    protected function getRoleChecker()
    {
        return (new RoleChecker($model));
    }

    protected function getUserChecker()
    {
        return (new UserChecker($model));
    }

}
