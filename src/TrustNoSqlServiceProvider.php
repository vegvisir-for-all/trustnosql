<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
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
