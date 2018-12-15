<?php

namespace Vegvisir\TrustNoSql\Checkers;

class BaseChecker
{

    protected $model;

    public function __construct($model)
    {
        $this->model = $model;
    }

}
