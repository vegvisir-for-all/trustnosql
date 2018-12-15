<?php

namespace Vegvisir\TrustNoSql\Traits\Providers;

trait CommandsProviderTrait
{

    protected function registerCommands()
    {
        $this->registerRoleCommands();
    }

    private function registerRoleCommands()
    {
        $this->app->singleton('command.trustnosql.role.create', function () {
            return new \Vegvisir\TrustNoSql\Commands\Role\Create();
        });

        $this->commands([
            'command.trustnosql.role.create'
        ]);
    }

}
