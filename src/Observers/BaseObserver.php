<?php

namespace Vegvisir\TrustNoSql\Observers;

class BaseObserver {

    public function __call($name, $arguments)
    {
        ($arguments[0])->currentModelFlushCache();
    }

}
