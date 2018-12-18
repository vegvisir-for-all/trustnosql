<?php

namespace Vegvisir\TrustNoSql\Traits\Providers;

trait CommandsProviderTrait
{

    protected $trustNoSqlCommands = [];

    protected function registerCommands()
    {
        $this->registerPermissionCommands();
        $this->registerRoleCommands();
        $this->registerTeamCommands();
        $this->registerUserCommands();

        $this->commands($this->trustNoSqlCommands);
    }

    private function registerPermissionCommands()
    {

        $this->app->singleton('command.trustnosql.permission.attach', function () {
            return new \Vegvisir\TrustNoSql\Commands\Permission\Attach();
        });

        $this->app->singleton('command.trustnosql.permission.create', function () {
            return new \Vegvisir\TrustNoSql\Commands\Permission\Create();
        });

        $this->app->singleton('command.trustnosql.permission.delete', function () {
            return new \Vegvisir\TrustNoSql\Commands\Permission\Delete();
        });

        $this->app->singleton('command.trustnosql.permission.detach', function () {
            return new \Vegvisir\TrustNoSql\Commands\Permission\Detach();
        });

        $this->app->singleton('command.trustnosql.permission.info', function () {
            return new \Vegvisir\TrustNoSql\Commands\Permission\Info();
        });

        $this->app->singleton('command.trustnosql.permissions', function () {
            return new \Vegvisir\TrustNoSql\Commands\Permission\ListAll();
        });

        $this->trustNoSqlCommands = array_merge($this->trustNoSqlCommands, [
            'command.trustnosql.permission.attach',
            'command.trustnosql.permission.create',
            'command.trustnosql.permission.delete',
            'command.trustnosql.permission.detach',
            'command.trustnosql.permission.info',
            'command.trustnosql.permissions'
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

    private function registerTeamCommands()
    {
        $this->app->singleton('command.trustnosql.team.create', function () {
            return new \Vegvisir\TrustNoSql\Commands\Team\Create();
        });

        $this->app->singleton('command.trustnosql.team.delete', function () {
            return new \Vegvisir\TrustNoSql\Commands\Team\Delete();
        });

        $this->app->singleton('command.trustnosql.teams', function () {
            return new \Vegvisir\TrustNoSql\Commands\Team\ListAll();
        });

        $this->trustNoSqlCommands = array_merge($this->trustNoSqlCommands, [
            'command.trustnosql.team.create',
            'command.trustnosql.team.delete',
            'command.trustnosql.teams'
        ]);
    }

    private function registerUserCommands()
    {
        $this->app->singleton('command.trustnosql.user.info', function () {
            return new \Vegvisir\TrustNoSql\Commands\User\Info();
        });

        $this->trustNoSqlCommands = array_merge($this->trustNoSqlCommands, [
            'command.trustnosql.user.info'
        ]);
    }

}
