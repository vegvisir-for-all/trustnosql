<?php

namespace Vegvisir\TrustNoSql\Commands\Role;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Commands\BaseCommand;
use Vegvisir\TrustNoSql\Models\Permission;
use Vegvisir\TrustNoSql\Models\Role;

class Info extends BaseCommand
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:role:info';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Shows a detailed information about the role';

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
        $rolesNames = $this->getRolesList();

        foreach($rolesNames as $roleName) {
            $this->line("Showing information for role '$roleName'");

            $role = $this->getRole($roleName, true);

            /**
             * 0. Info
             */

            $this->line("Name: $roleName");
            $this->line("Display name: $role->display_name");
            $this->line("Description: $role->description");

            /**
             * 1. Permissions
             */

            $permissionsNames = $role->getRoleCurrentPermissions();

            $permissions = Permission::whereIn('name', $permissionsNames)->get(['name', 'display_name', 'description'])->toArray();

            $this->line('PERMISSIONS');
            $this->table(['Id', 'Permission', 'Display name', 'Description'], $permissions);

            $users = $role->users()->get(['name', 'email'])->toArray();

            $this->line('USERS');
            $this->table(['Id', 'Name', 'E-mail'], $users);
        }
    }
}
