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

class BaseMiddleware {

    /**
     * Check if the request has authorization to continue.
     *
     * @param  string $type
     * @param  string $rolesPermissions
     * @param  string|null $team
     * @param  string|null $options
     * @return boolean
     */
    // protected function authorization($type, $rolesPermissions, $team, $options)
    // {
    //     list($team, $requireAll, $guard) = $this->assignRealValuesTo($team, $options);

    //     $method = $type == 'roles' ? 'hasRole' : 'hasPermission';

    //     return !Auth::guard($guard)->guest()
    //         && Auth::guard($guard)->user()->$method($rolesPermissions, $team, $requireAll);
    // }

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
        return !Auth::guest() &&
            $this->parseInstruction($instruction, Auth::user());
    }

    protected function parseInstruction($instruction, $user)
    {

        $instruction = preg_replace('/([A-Za-z*]{1,}:[A-Za-z*\/]{1,})/im', '(\1)', preg_replace('/\s+/', '', $instruction));

        foreach([
            '|' => ' || ',
            '&' => ' && '
        ] as $from => $to) {
            $instruction = str_replace($from, $to, $instruction);
        }

        $hasRole = function ($roleName) use ($user) {
            return $user->hasRole($roleName);
        };
        $hasRoleAndOwns = function ($roleName) use ($user) {
            return $user->hasRole($roleName);
        };
        $hasPermission = function ($permissionName) use ($user) {
            return $user->hasRole($permissionName);
        };
        $hasPermissionAndOwns = function ($permissionName) use ($user) {
            return $user->hasRole($permissionName);
        };
        $hasTeam = function ($teamName) use ($user) {
            return $user->hasTeam($teamName);
        };

        /**
         * Step 4
         * Parsing to true/falses
         */
        $matches = [];

        while(0 !== preg_match('/(\({1}[A-Za-z0-9*:\/]*\){1})/im', $instruction, $matches) && !empty($matches)) {
            $partialExploded = explode(':', substr($matches[0], 1, strlen($matches[0]) - 2 ));
            $instruction = str_replace($matches[0], (string) $${str_replace('*', 'AndOwns', 'has' . ucfirst($partialExploded[0]))}($partialExploded[1]) ? 'true' : 'false', $instruction);
        }

        eval('$result = (bool)(' . $instruction .');');

        if(is_bool($result)) {
            return $result;
        }
    }

}
