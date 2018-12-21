<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Traits\Providers;

use Vegvisir\TrustNoSql;

trait TrustNoSqlProviderTrait
{
    /**
     * Register the application bindings.
     */
    private function registerTrustNoSql()
    {
        $this->app->bind('trustnosql', function ($app) {
            return new TrustNoSql($app);
        });
        $this->app->alias('trustnosql', 'Vegvisir\TrustNoSql');
    }
}
