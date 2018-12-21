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
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Middleware\Parser\LogicStringParser;

class BaseMiddleware
{
    /**
     * The request is unauthorized, so it handles the aborting/redirecting.
     *
     * @return \Illuminate\Http\Response
     */
    protected function unauthorized()
    {
        $handling = Config::get('trustnosql.middleware.handling', 'abort');
        $handler = Config::get("trustnosql.middleware.handlers.{$handling}");

        if ('abort' === $handling) {
            return App::abort($handler['code']);
        }

        $redirect = Redirect::to($handler['url']);

        if (!empty($handler['message']['content'])) {
            $redirect->with($handler['message']['type'], $handler['message']['content']);
        }

        return $redirect;
    }

    /**
     * Return authorized user (or null if unauthorized).
     *
     * @param string $guard optional guard name
     *
     * @return null|User
     */
    protected static function authUser($guard = null)
    {
        if (null === $guard) {
            $guard = Config::get('auth.defaults.guard');
        }

        if (Auth::guard($guard)->guest()) {
            return null;
        }

        return Auth::guard($guard)->user();
    }

    /**
     * Check whether current user passes conditions given in the middleware call.
     * It calls $this->unauthorized() if conditions were not met, or returns $next($request)
     * for the next middleware to run.
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure                  $next
     * @param string                   $expression
     * @param null|string              $guard
     * @param bool                     $negateExpression
     *
     * @return mixed
     */
    protected function authorize($request, Closure $next, $expression, $guard = null, $negateExpression = false)
    {
        $user = static::authUser($guard);

        $parsingResult = LogicStringParser::expressionToBool($expression, Helper::getUserLogicProxy($user));

        if ($parsingResult === $negateExpression) {
            $this->unauthorized();
        }

        return $next($request);
    }

    /**
     * Translate entity pipeline to expression (like in the trust middleware).
     *
     * @param string $entityName       Name of the entity
     * @param string $entitiesPipeline Pipeline of entity names
     * @param bool   $requireAll       Checks if all entities in pipeline must be attached to model (& operands)
     *
     * @return string
     */
    protected function parseToExpression($entityName, $entitiesPipeline, $requireAll = false)
    {
        return LogicStringParser::pipelineToExpression($entityName, $entitiesPipeline, $requireAll);
    }
}
