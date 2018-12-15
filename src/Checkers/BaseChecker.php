<?php

namespace Vegvisir\TrustNoSql\Checkers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Jenssegers\Mongodb\Eloquent\Model;
use Vegvisir\TrustNoSql\Models\Permission;

class BaseChecker
{

    /**
     * Role model used for checking.
     *
     * @var \Jenssegers\Mongodb\Eloquent\Model
     * @return void
     */
    protected $model;

    /**
     * Available permissions wildcards array.
     *
     * @var array
     */
    protected $wildcards = [];

    /**
     * Creates new instance.
     *
     * @param $model \Jenssegers\Mongodb\Eloquent\Model Role model used for checking
     */
    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->wildcards = Permission::getWildcards();
    }

}
