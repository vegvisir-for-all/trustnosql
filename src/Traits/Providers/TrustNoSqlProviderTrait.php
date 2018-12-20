<?php

namespace Vegvisir\TrustNoSql\Traits\Providers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql;

trait TrustNoSqlProviderTrait
{

    /**
     * Register the application bindings.
     *
     * @return void
     */
    private function registerTrustNoSql()
    {
        $this->app->bind('trustnosql', function ($app) {
            return new TrustNoSql($app);
        });
        $this->app->alias('trustnosql', 'Vegvisir\TrustNoSql');
    }

}
