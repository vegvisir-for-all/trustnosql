<?php

namespace Vegvisir\TrustNoSql\Observers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Support\Facades\Config;

class BaseObserver {

    /**
     * Function always flushes cache for model whenever any event is fired
     */
    public function __call($name, $arguments)
    {
        ($arguments[0])->currentModelFlushCache();

        if(!Config::get('trustnosql.events.use_events', true)) {
            return false;
        }

        return call_user_fun_array($this->$name, $arguments);
    }

}
