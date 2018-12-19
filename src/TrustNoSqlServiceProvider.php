<?php

namespace Vegvisir\TrustNoSql;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use Vegvisir\TrustNoSql\Observers\UserObserver;
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
            __DIR__ . '/../config/trustnosql.php' => config_path('trustnosql.php')
        ], 'trustnosql');
    }

    public function register()
    {
        if(Config::get('trustnosql.cli.use_cli', true)) {
            $this->registerCommands();
        }

        $this->registerTrustNoSql();
    }

    public function provides()
    {
        return array_values(array_merge($this->trustNoSqlCommands));
    }
}
