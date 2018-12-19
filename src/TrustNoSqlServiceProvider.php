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
use Vegvisir\TrustNoSql\Traits\Providers\CommandsProviderTrait;

class TrustNoSqlServiceProvider extends ServiceProvider
{

    use CommandsProviderTrait;

    public function boot()
    {

    }

    public function register()
    {
        if(Config::get('trustnosql.cli.use_cli')) {
            $this->registerCommands();
        }
    }

}
