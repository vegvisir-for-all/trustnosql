<?php

namespace Vegvisir\TrustNoSql\Middleware;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Closure;

class Role extends BaseMiddleware {

    public function handle($request, Closure $next, $roles, $team = null, $options)
    {
        if (!$this->authorization('roles', $roles, $team, $options)) {
            return $this->unauthorized();
        }

        return $next($request);
    }

}
