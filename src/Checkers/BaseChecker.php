<?php

namespace Vegvisir\TrustNoSql\Checkers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Jenssegers\Mongodb\Eloquent\Model;

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
     * Creates new instance.
     *
     * @param $model \Jenssegers\Mongodb\Eloquent\Model Role model used for checking
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

}
