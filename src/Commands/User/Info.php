<?php

namespace Vegvisir\TrustNoSql\Commands\User;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Commands\BaseCommand;
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Models\Permission;
use Vegvisir\TrustNoSql\Models\Role;

class Info extends BaseCommand
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:user:info';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Shows a detailed information about user';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute a console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userEmails = $this->getUsersList();

        foreach($userEmails as $email) {
            $this->line("Showing information for user '$email'");

            $user = $this->getUser($email, true);

            /**
             * 0. Info
             */

            $this->line("Name: $user->name");
            $this->line("E-mail: $user->email");

            /**
             * 1. Roles
             */

            $rolesNames = $user->getUserCurrentRoles();

            $roles = Role::whereIn('name', $rolesNames)->get(['name', 'display_name', 'description'])->toArray();

            $this->line('ROLES');
            $this->table(['Id', 'Permission', 'Display name', 'Description'], $roles);

            /**
             * 2. Permissions
             */

            $permissionsNames = $user->getUserCurrentPermissions();

            $permissions = Permission::whereIn('name', $permissionsNames)->get(['name', 'display_name', 'description'])->toArray();

            $this->line('PERMISSIONS');
            $this->table(['Id', 'Permission', 'Display name', 'Description'], $permissions);
        }
    }
}
