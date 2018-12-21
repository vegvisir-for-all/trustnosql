<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Commands\Role;

use Vegvisir\TrustNoSql\Commands\BaseCommand;
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Models\Permission;
use Vegvisir\TrustNoSql\Models\Role;
use Vegvisir\TrustNoSql\Models\Team;

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
        $rolesNames = $this->getEntitiesList(new Role());

        foreach ($rolesNames as $roleName) {
            $this->line("Showing information for role '${roleName}'");

            $role = $this->getRole($roleName, true);

            // 0. Info

            $this->line("Name: ${roleName}");
            $this->line("Display name: {$role->display_name}");
            $this->line("Description: {$role->description}");

            /**
             * 1. Permissions.
             */
            $permissionsNames = $role->getRoleCurrentPermissions();

            $permissions = Permission::whereIn('name', $permissionsNames)->get(['name', 'display_name', 'description'])->toArray();

            $this->line('PERMISSIONS');
            $this->table(['Id', 'Permission', 'Display name', 'Description'], $permissions);

            /**
             * 2. Teams.
             */
            $teamsNames = $role->getRoleCurrentTeams();

            $teams = Team::whereIn('name', $teamsNames)->get(['name', 'display_name', 'description'])->toArray();

            $this->line('TEAMS');
            $this->table(['Id', 'Team', 'Display name', 'Description'], $teams);

            /**
             * 3. Users.
             */
            $usersEmails = $role->getRoleCurrentUsers();
            $userModel = Helper::getUserModel();

            $users = $userModel->whereIn('email', $usersEmails)->get(['name', 'email'])->toArray();

            $this->line('USERS');
            $this->table(['Id', 'Name', 'E-mail'], $users);
        }
    }
}
