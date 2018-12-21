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
