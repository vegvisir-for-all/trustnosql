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

class Ability extends BaseMiddleware
{

    /**
     * Handle incoming request
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Closure $next
     * @param  string  $roles
     * @param  string  $permissions
     * @param  string  $teams
     * @param  bool    $requireAll
     * @param  string  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $roles, $permissions = '', $teams = '', $requireAll = false, $guard = null)
    {
        $rolesExpression = $this->parseToExpression('role', $roles);
        $permissionsExpression = $this->parseToExpression('permission', $permissions);
        $teamsExpression = $this->parseToExpression('team', $teams);

        $operand = filter_var($requireAll, FILTER_VALIDATE_BOOLEAN) ? '&' : '|';

        $expression = "( $rolesExpression ) $operand ( $permissionsExpression ) $operand ( $teamsExpression )";

        return $this->authorize($request, $next, '('.$expression.')', $guard);
    }
}
