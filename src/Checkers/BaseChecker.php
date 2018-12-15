<?php

namespace Vegvisir\TrustNoSql\Checkers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Jenssegers\Mongodb\Eloquent\Model;
use Vegvisir\TrustNoSql\Helpers\Helper;
use Vegvisir\TrustNoSql\Models\Permission;

class BaseChecker
{

    /**
     * Role model used for checking.
     *
     * @var \Jenssegers\Mongodb\Eloquent\Model
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
     * @param \Jenssegers\Mongodb\Eloquent\Model $model Role model used for checking
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->wildcards = Helper::getPermissionWildcards();
    }

}
