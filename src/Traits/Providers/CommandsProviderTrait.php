<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Traits\Providers;

use Illuminate\Support\Facades\Config;

trait CommandsProviderTrait
{
    /**
     * List of commands to be bound.
     *
     * @var array
     */
    protected $trustNoSqlCommands = [];

    /**
     * Base signature for TrustNoSql commands.
     *
     * @var string
     */
    protected $baseSignature = 'command.trustnosql.';

    /**
     * Base class namespace for TrustNoSql commands.
     *
     * @var string
     */
    protected $baseClassNamespace = '\\Vegvisir\\TrustNoSql\\Commands\\';

    /**
     * Registers commands (called by main service provider).
     */
    protected function registerCommands()
    {
        $this->baseSignature = Config::get('trustnosql.cli.signature', $this->baseSignature);

        $this->registerPermissionCommands();
        $this->registerRoleCommands();
        $this->registerTeamCommands();
        $this->registerUserCommands();

        $this->commands($this->trustNoSqlCommands);
    }

    /**
     * Register permission commands.
     */
    private function registerPermissionCommands()
    {
        $this->registerModelCommands('permission', [
            '.attach' => 'Attach',
            '.create' => 'Create',
            '.delete' => 'Delete',
            '.detach' => 'Detach',
            '.info' => 'Info',
            's' => 'ListAll',
        ]);
    }

    /**
     * Register role commands.
     */
    private function registerRoleCommands()
    {
        $this->registerModelCommands('role', [
            '.attach' => 'Attach',
            '.create' => 'Create',
            '.delete' => 'Delete',
            '.detach' => 'Detach',
            '.info' => 'Info',
            's' => 'ListAll',
        ]);
    }

    /**
     * Register team commands.
     */
    private function registerTeamCommands()
    {
        $this->registerModelCommands('team', [
            '.attach' => 'Attach',
            '.create' => 'Create',
            '.delete' => 'Delete',
            '.detach' => 'Detach',
            '.info' => 'Info',
            's' => 'ListAll',
        ]);
    }

    /**
     * Register user commands.
     */
    private function registerUserCommands()
    {
        $this->registerModelCommands('user', [
            '.info' => 'Info',
        ]);
    }

    /**
     * Register model commands, by given signature and available commands.
     *
     * @param string $signatureNamespace Namespace of the signature
     * @param array  $availableCommands  Available commands
     */
    private function registerModelCommands($signatureNamespace, $availableCommands)
    {
        foreach ($availableCommands as $command => $className) {
            $className = $this->baseClassNamespace.ucfirst($signatureNamespace).'\\'.$className;

            $this->app->singleton($this->baseSignature.$signatureNamespace.$command, function () use ($className) {
                return new $className();
            });

            $this->trustNoSqlCommands = array_merge($this->trustNoSqlCommands, [
                $this->baseSignature.$signatureNamespace.$command,
            ]);
        }
    }
}
