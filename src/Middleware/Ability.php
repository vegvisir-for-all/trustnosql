<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Middleware;

use Closure;

class Ability extends BaseMiddleware
{
    /**
     * Handle incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     * @param string                   $roles
     * @param string                   $permissions
     * @param string                   $teams
     * @param bool                     $requireAll
     * @param string                   $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $roles, $permissions = '', $teams = '', $requireAll = false, $guard = null)
    {
        $rolesExpression = $this->parseToExpression('role', $roles);
        $permissionsExpression = $this->parseToExpression('permission', $permissions);
        $teamsExpression = $this->parseToExpression('team', $teams);

        $operand = filter_var($requireAll, FILTER_VALIDATE_BOOLEAN) ? '&' : '|';

        $expression = "( ${rolesExpression} ) ${operand} ( ${permissionsExpression} ) ${operand} ( ${teamsExpression} )";

        return $this->authorize($request, $next, '('.$expression.')', $guard);
    }
}
