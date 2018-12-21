<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
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
    public function __call($name, $arguments)
    {
        ($arguments[0])->currentModelFlushCache();

        if (!Config::get('trustnosql.events.use_events', true)) {
            return false;
        }

        return call_user_fun_array($this->{$name}, $arguments);
    }
}
