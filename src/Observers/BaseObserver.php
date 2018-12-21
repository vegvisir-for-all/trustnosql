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
