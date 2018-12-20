<?php

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

        if ($handling == 'abort') {
            return App::abort($handler['code']);
        }

        $redirect = Redirect::to($handler['url']);

        if (!empty($handler['message']['content'])) {
            $redirect->with($handler['message']['type'], $handler['message']['content']);
        }

        return $redirect;
    }

    /**
     * Return authorized user (or null if unauthorized)
     *
     * @param string $guard Optional guard name.
     * @return Object
     */
    protected static function authUser($guard = null)
    {
        if($guard == null) {
            $guard = Config::get('auth.defaults.guard');
        }

        if(Auth::guard($guard)->guest()) {
            return null;
        }

        return Auth::guard($guard)->user();
    }

    /**
     * Check whether current user passes conditions given in the middleware call.
     * It calls $this->unauthorized() if conditions were not met, or returns $next($request)
     * for the next middleware to run
     *
     * @param \Illuminate\Http\Request $request
     * @param Closure $next
     * @param string $expression
     * @param string|null $guard
     * @param bool $negateExpression
     */
    protected function authorize($request, Closure $next, $expression, $guard = null, $negateExpression = false)
    {
        $user = static::authUser($guard);

        $parsingResult = LogicStringParser::expressionToBool($expression, Helper::getUserLogicProxy($user));

        if($parsingResult == $negateExpression) {
            $this->unauthorized();
        }

        return $next($request);
    }

    protected function parseToExpression($entityName, $entitiesList, $requireAll = false)
    {
        /**
         * Entities can be given in one of two following methods:
         *
         * 1. 'vegvisir,backpet' with additional parameter $requireAll
         * 2. 'vegvisir&backpet' as AND
         * 3. 'vegvisir|backpet' as OR pipeline
         *
         * Function should throw error when both & and | pipeline operators are present
         */

        return LogicStringParser::pipelineToExpression($entityName, $entitiesList, $requireAll);
    }

}
