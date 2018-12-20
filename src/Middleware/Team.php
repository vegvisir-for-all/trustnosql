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

class Team extends BaseMiddleware
{

    /**
     * Handle incoming request
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param string $expression
     * @param string|null $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $teams, $guard = null)
    {
        $expression = $this->parseToExpression('team', $teams);
        return $this->authorize($request, $next, '('.$expression.')', $guard);
    }
}
