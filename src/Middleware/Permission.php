<?php

namespace Vegvisir\TrustNoSql\Middleware;

use Closure;
use Vegvisir\TrustNoSql\Middleware\BaseMiddleware;

class Team extends BaseMiddleware
{

    /**
     * Handle incoming request
     */
    public function handle($request, Closure $next, $teams, $guard = null)
    {

        $expression = $this->parseToExpression('permission', $teams);
        return $this->authorize($request, $next, '('.$expression.')', $guard, true);
    }
}
