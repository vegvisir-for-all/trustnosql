<?php

namespace Vegvisir\TrustNoSql\Middleware;

use Closure;
use Vegvisir\TrustNoSql\Middleware\BaseMiddleware;

class Trust extends BaseMiddleware
{

    /**
     * Handle incoming request
     */
    public function handle($request, Closure $next, $expression, $guard = null)
    {
        return $this->authorize($request, $next, $expression, $guard);
    }

}
