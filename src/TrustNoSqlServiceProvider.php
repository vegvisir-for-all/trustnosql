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

namespace Vegvisir\TrustNoSql;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Vegvisir\TrustNoSql\Traits\Providers\CommandsProviderTrait;
use Vegvisir\TrustNoSql\Traits\Providers\TrustNoSqlProviderTrait;

class TrustNoSqlServiceProvider extends ServiceProvider
{
    use CommandsProviderTrait, TrustNoSqlProviderTrait;

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/trustnosql.php', 'trustnosql');

        $this->publishes([
            __DIR__.'/../config/trustnosql.php' => config_path('trustnosql.php'),
        ], 'trustnosql');
    }

    public function register()
    {
        if (Config::get('trustnosql.cli.use_cli', true)) {
            $this->registerCommands();
        }

        $this->registerTrustNoSql();
    }

    public function provides()
    {
        return array_values(array_merge($this->trustNoSqlCommands));
    }
}
