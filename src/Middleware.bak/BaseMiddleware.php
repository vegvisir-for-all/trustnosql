<?php

namespace Vegvisir\TrustNoSql\Middleware;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Redirect;
use Vegvisir\TrustNoSql\Middleware\Parser\LogicParser;

class BaseMiddleware {

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
     * Generate an array with the real values of the parameters given to the middleware.
     *
     * @param  string $team
     * @param  string $options
     * @return array
     */
    protected function assignRealValuesTo($team, $options)
    {
        return [
            (Str::contains($team, ['require_all', 'guard:']) ? null : $team),
            (Str::contains($team, 'require_all') ?: Str::contains($options, 'require_all')),
            (Str::contains($team, 'guard:') ? $this->extractGuard($team) : (
                Str::contains($options, 'guard:')
                ? $this->extractGuard($options)
                : Config::get('auth.defaults.guard')
            )),
        ];
    }

    /**
     * Extract the guard type from the given string.
     *
     * @param  string $string
     * @return string
     */
    protected function extractGuard($string)
    {
        $options = Collection::make(explode('|', $string));

        return $options->reject(function ($option) {
            return strpos($option, 'guard:') === false;
        })->map(function ($option) {
            return explode(':', $option)[1];
        })->first();

    }

    protected function authorization($instruction)
    {
        return (!Auth::guest() &&
            $this->parseInstruction($instruction, Auth::user()));
    }

    protected function logicProxy($user)
    {
        return function ($middlewareNamespace, $entityName) use ($user) {
            $functionName = 'has' . ucfirst($middlewareNamespace);
            return $user->$functionName($entityName);
        };
    }

    protected function parseInstruction($instruction, $user)
    {
        return LogicParser::parseLogicString($instruction, $this->logicProxy($user));
    }
}
