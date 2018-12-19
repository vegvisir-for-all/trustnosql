<?php

namespace Vegvisir\TrustNoSql\Traits\Providers;

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
