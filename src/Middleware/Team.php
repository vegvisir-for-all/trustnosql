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

class Team extends BaseMiddleware
{
    /**
     * Handle incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     * @param string                   $expression
     * @param null|string              $guard
     * @param mixed                    $teams
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $teams, $guard = null)
    {
        $expression = $this->parseToExpression('team', $teams);

        return $this->authorize($request, $next, '('.$expression.')', $guard);
    }
}
