<?php

namespace Vegvisir\TrustNoSql\Observers;

use Illuminate\Support\Facades\Config;

class BaseObserver {

    public function __call($name, $arguments)
    {
        ($arguments[0])->currentModelFlushCache();

        if(!Config::get('trustnosql.events.use_events', true)) {
            return false;
        }
    }

}
