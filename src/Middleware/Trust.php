<?php

namespace Vegvisir\TrustNoSql\Middleware;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Closure;
use Vegvisir\TrustNoSql\Middleware\BaseMiddleware;

class Trust extends BaseMiddleware {

    public function handle($request, Closure $next, $instruction)
    {
        if(!$this->authorization($instruction)) {
            return $this->unauthorized();
        }
    }
}
