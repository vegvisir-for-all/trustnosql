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
        dd(Auth::guest() &&
            $this->parseInstruction($instruction, Auth::user()));
    }

    protected function parseInstruction($instruction, $user)
    {

        $result = LogicParser::parseLogicString($instruction);

        $instruction = preg_replace('/([A-Za-z*]{1,}:[A-Za-z*\/]{1,})/im', '(\1)', preg_replace('/\s+/', '', $instruction));

        foreach([
            '|' => '||',
            '&' => '&&'
        ] as $from => $to) {
            $instruction = str_replace($from, $to, $instruction);
        }

        $user = \App\User::first();

        $hasRole = function ($roleName, $user) {
            return $user->hasRole($roleName);
        };
        $hasRoleAndOwns = function ($roleName, $user) {
            return $user->hasRole($roleName);
        };
        $hasPermission = function ($permissionName, $user) {
            return $user->hasRole($permissionName);
        };
        $hasPermissionAndOwns = function ($permissionName, $user) {
            return $user->hasRole($permissionName);
        };
        $hasTeam = function ($teamName, $user) {
            return $user->hasTeam($teamName);
        };

        while(false !== strpos($instruction, '(') && false !== strpos($instruction, ')')) {

            /**
             * Step 0
             * Reduce basic brackets with middleware instructions
             */
            while(1 == preg_match('/\([A-Za-z0-9*]*\:[A-Za-z0-9*\/]*\)/im', $instruction, $matches)) {

                $strResult = rand(0,1) == 1 ? 'true' : 'false';
                $instruction = str_replace($matches[0], $strResult, $instruction);
            }

            /**
             * Step 1
             * Reduce basic brackets with single bool
             */
            while(false !== strpos($instruction, '(false)') || false !== strpos($instruction, '(true)')) {
                $instruction = str_replace('(false)', 'false', $instruction);
                $instruction = str_replace('(true)', 'true', $instruction);
            }

            /**
             * Step 2
             * Reduce bool&&bool and bool||bool
             */
            $matches = [];
            while(1 == preg_match_all('/\((true|false)([\&\|]{2})(true|false)\)/im', $instruction, $matches)) {

                foreach($matches[0] as $key => $fullString) {
                    $result = $matches[2][$key] == '||'
                        ? filter_var($matches[1][$key], FILTER_VALIDATE_BOOLEAN) || filter_var($matches[3][$key], FILTER_VALIDATE_BOOLEAN)
                        : filter_var($matches[1][$key], FILTER_VALIDATE_BOOLEAN) && filter_var($matches[3][$key], FILTER_VALIDATE_BOOLEAN);

                        $strResult = $result ? 'true' : 'false';

                        $instruction = str_replace($fullString, $strResult, $instruction);
                }
            }

            /**
             * Step 3
             * We must put all loose && operations into brackets
             */
            $instructionBefore = $instruction;
            $instruction = preg_replace('/(true|false)([\&]{2})(true|false)/im', '(\1\2\3)', $instruction);

            if($instructionBefore !== $instruction) {
                // to first step
                continue;
            }

            /**
             * Step 3
             * We must put first loose || operation into brackets
             */
            $instruction = preg_replace('/(true|false)([\|]{2})(true|false)/im', '(\1\2\3)', $instruction, 1);

            if($instructionBefore !== $instruction) {
                // to first step
                continue;
            }


            break;
        }

        return filter_var($instruction, FILTER_VALIDATE_BOOLEAN);
    }

}
