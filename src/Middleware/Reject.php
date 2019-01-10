<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Middleware;

use Closure;

class Reject extends BaseMiddleware
{
    /**
     * Handle incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     * @param string                   $expression
     * @param null|string              $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $expression, $guard = null)
    {
        return $this->authorize($request, $next, '(' . $expression . ')', $guard, true);
    }
}
