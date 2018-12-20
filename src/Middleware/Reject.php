<?php

namespace Vegvisir\TrustNoSql\Middleware;

use Closure;
use Vegvisir\TrustNoSql\Middleware\BaseMiddleware;

class Reject extends BaseMiddleware
{

    /**
     * Handle incoming request
     */
    public function handle($request, Closure $next, $expression, $guard)
    {
        return $this->authorize($request, $next, $expression, $guard, true);
    }

}
