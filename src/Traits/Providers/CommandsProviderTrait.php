<?php

namespace Vegvisir\TrustNoSql\Traits\Providers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
trait CommandsProviderTrait
{

    /**
     * List of commands to be bound.
     *
     * @var array
     */
    protected $trustNoSqlCommands = [];

    /**
     * Base signature for TrustNoSql commands
     *
     * @var string
     */
    protected $baseSignature = 'command.trustnosql.';

    /**
     * Base class namespace for TrustNoSql commands
     *
     * @var string
     */
    protected $baseClassNamespace = '\\Vegvisir\\TrustNoSql\\Commands\\';

    /**
     * Registers commands (called by main service provider)
     */
    protected function registerCommands()
    {
        $this->registerPermissionCommands();
        $this->registerRoleCommands();
        $this->registerTeamCommands();
        $this->registerUserCommands();

        $this->commands($this->trustNoSqlCommands);
    }

    /**
     * Register permission commands
     */
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

    /**
     * Register role commands
     */
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

    /**
     * Register team commands
     */
    private function registerTeamCommands()
    {
        $this->registerModelCommands('team', [
            '.attach' => 'Attach',
            '.create' => 'Create',
            '.delete' => 'Delete',
            '.info' => 'Info',
            's' => 'ListAll'
        ]);
    }

    /**
     * Register user commands
     */
    private function registerUserCommands()
    {
        $this->registerModelCommands('user', [
            '.info' => 'Info'
        ]);
    }

    /**
     * Register model commands, by given signature and available commands
     *
     * @param string $signatureNamespace Namespace of the signature
     * @param array $availableCommands Available commands
     */
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

}
