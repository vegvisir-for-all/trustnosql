<?php

namespace Vegvisir\TrustNoSql\Middleware;

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
