<?php

namespace Vegvisir\TrustNoSql\Middleware;

use Closure;
use Vegvisir\TrustNoSql\Middleware\BaseMiddleware;

class Ability extends BaseMiddleware
{

    /**
     * Handle incoming request
     */
    public function handle($request, Closure $next, $roles, $permissions = '', $teams = '', $requireAll = false, $guard = null)
    {

        $rolesExpression = $this->parseToExpression('role', $roles);
        $permissionsExpression = $this->parseToExpression('permission', $permissions);
        $teamsExpression = $this->parseToExpression('team', $teams);

        $operand = filter_var($requireAll, FILTER_VALIDATE_BOOLEAN) ? '&' : '|';

        $expression = "( $rolesExpression ) $operand ( $permissionsExpression ) $operand ( $teamsExpression )";

        // $expression = $this->parseToExpression('team', $teams);
        return $this->authorize($request, $next, '('.$expression.')', $guard, true);
    }
}
