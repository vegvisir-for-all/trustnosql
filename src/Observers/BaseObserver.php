<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Observers;

use Illuminate\Support\Facades\Config;

class BaseObserver
{
    /**
     * Function always flushes cache for model whenever any event is fired.
     *
     * @param mixed $name
     * @param mixed $arguments
     */
    public static function __callStatic($name, $arguments)
    {
        ($arguments[0])->currentModelFlushCache();

        if (!Config::get('trustnosql.events.use_events', true)) {
            return false;
        }

        if(method_exists(__CLASS__, $name)) {
            return call_user_func_array(self::$name, $arguments);
        }

        return true;

    }
}
