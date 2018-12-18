<?php

namespace Vegvisir\TrustNoSql\Traits\Providers;

trait CommandsProviderTrait
{

    protected $trustNoSqlCommands = [];

    protected $baseSignature = 'command.trustnosql.';

    protected $baseClassNamespace = '\\Vegvisir\\TrustNoSql\\Commands\\';

    protected function registerCommands()
    {
        $this->registerPermissionCommands();
        $this->registerRoleCommands();
        $this->registerTeamCommands();
        $this->registerUserCommands();

        $this->commands($this->trustNoSqlCommands);
    }

    private function registerModelCommands($signatureNamespace, $availableCommands)
    {

        foreach($availableCommands as $command => $className) {

            $className = $this->baseClassNamespace . ucfirst($signatureNamespace) . '\\' . $className;

            $this->app->singleton($this->baseSignature . $signatureNamespace . $command, function () use ($className) {
                return new $className();
            });

            $this->trustNoSqlCommands = array_merge($this->trustNoSqlCommands, [
                $this->baseSignature . $signatureNamespace . $command
            ]);

        }
    }

    private function registerPermissionCommands()
    {
        $this->registerModelCommands('permission', [
            '.attach' => 'Attach',
            '.create' => 'Create',
            '.delete' => 'Delete',
            '.detach' => 'Detach',
            '.info' => 'Info',
            's' => 'ListAll'
        ]);
    }

    private function registerRoleCommands()
    {
        $this->registerModelCommands('role', [
            '.attach' => 'Attach',
            '.create' => 'Create',
            '.delete' => 'Delete',
            '.detach' => 'Detach',
            '.info' => 'Info',
            's' => 'ListAll'
        ]);
    }

    private function registerTeamCommands()
    {
        $this->registerModelCommands('team', [
            '.create' => 'Create',
            '.delete' => 'Delete',
            's' => 'ListAll'
        ]);
    }

    private function registerUserCommands()
    {
        $this->registerModelCommands('user', [
            '.info' => 'Info'
        ]);
    }

}
