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
        $signatureNamespace = 'permission';

        $availableCommands = [
            '.attach' => 'Attach',
            '.create' => 'Create',
            '.delete' => 'Delete',
            '.detach' => 'Detach',
            '.info' => 'Info',
            's' => 'ListAll'
        ];

        $this->registerModelCommands($signatureNamespace, $availableCommands);
    }

    private function registerRoleCommands()
    {
        $signatureNamespace = 'role';

        $availableCommands = [
            '.attach' => 'Attach',
            '.create' => 'Create',
            '.delete' => 'Delete',
            '.detach' => 'Detach',
            '.info' => 'Info',
            's' => 'ListAll'
        ];

        $this->registerModelCommands($signatureNamespace, $availableCommands);
    }

    private function registerTeamCommands()
    {
        $signatureNamespace = 'team';

        $availableCommands = [
            '.create' => 'Create',
            '.delete' => 'Delete',
            's' => 'ListAll'
        ];

        $this->registerModelCommands($signatureNamespace, $availableCommands);
    }

    private function registerUserCommands()
    {
        $signatureNamespace = 'user';

        $availableCommands = [
            '.info' => 'Info'
        ];

        $this->registerModelCommands($signatureNamespace, $availableCommands);
    }

}
