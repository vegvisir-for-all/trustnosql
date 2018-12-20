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

class Role extends BaseMiddleware
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
    public function handle($request, Closure $next, $expression, $guard = null)
    {
        $expression = $this->parseToExpression('role', $expression);
        return $this->authorize($request, $next, '('.$expression.')', $guard);
    }
}
