<?php

namespace Vegvisir\TrustNoSql\Traits\Providers;

trait CommandsProviderTrait
{

    protected $trustNoSqlCommands = [];

    protected function registerCommands()
    {
        $this->registerPermissionCommands();
        $this->registerRoleCommands();

        $this->commands($this->trustNoSqlCommands);
    }

    private function registerPermissionCommands()
    {

        $this->app->singleton('command.trustnosql.permission.create', function () {
            return new \Vegvisir\TrustNoSql\Commands\Permission\Create();
        });

        $this->app->singleton('command.trustnosql.permission.delete', function () {
            return new \Vegvisir\TrustNoSql\Commands\Permission\Delete();
        });

        $this->trustNoSqlCommands = array_merge($this->trustNoSqlCommands, [
            'command.trustnosql.permission.delete'
        ]);
    }

    private function registerRoleCommands()
    {
        $this->app->singleton('command.trustnosql.role.attach', function () {
            return new \Vegvisir\TrustNoSql\Commands\Role\Attach();
        });

        $this->app->singleton('command.trustnosql.role.create', function () {
            return new \Vegvisir\TrustNoSql\Commands\Role\Create();
        });

        $this->app->singleton('command.trustnosql.role.delete', function () {
            return new \Vegvisir\TrustNoSql\Commands\Role\Delete();
        });

        $this->app->singleton('command.trustnosql.role.detach', function () {
            return new \Vegvisir\TrustNoSql\Commands\Role\Detach();
        });

        $this->app->singleton('command.trustnosql.role.info', function () {
            return new \Vegvisir\TrustNoSql\Commands\Role\Info();
        });

        $this->app->singleton('command.trustnosql.roles', function () {
            return new \Vegvisir\TrustNoSql\Commands\Role\ListAll();
        });

        $this->trustNoSqlCommands = array_merge($this->trustNoSqlCommands, [
            'command.trustnosql.role.attach',
            'command.trustnosql.role.create',
            'command.trustnosql.role.delete',
            'command.trustnosql.role.detach',
            'command.trustnosql.role.info',
            'command.trustnosql.roles'
        ]);
    }

}
