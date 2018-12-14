<?php

namespace Vegvisir\TrustNoSql;

use Illuminate\Support\Facades\Facade;

class TrustNoSqlFacade extends Facade
{

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'trustnosql';
    }

}
