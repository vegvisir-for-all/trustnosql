<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * (c) Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 *
 * This source file is subject to the GPL-3.0-or-later license that is bundled
 * with this source code in the file LICENSE.
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
    public function handle($request, Closure $next, $expression, $guard)
    {
        return $this->authorize($request, $next, $expression, $guard, true);
    }
}
