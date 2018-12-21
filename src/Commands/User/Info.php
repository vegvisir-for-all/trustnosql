<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * (c) Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 *
 * This source file is subject to the GPL-3.0-or-later license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vegvisir\TrustNoSql\Commands\User;

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
        $userEmails = $this->getEntitiesList(Helper::getUserModel());

        foreach ($userEmails as $email) {
            $this->line("Showing information for user '${email}'");

            $user = $this->getUser($email, true);

            // 0. Info

            $this->line("Name: {$user->name}");
            $this->line("E-mail: {$user->email}");

            /**
             * 1. Roles.
             */
            $rolesNames = $user->getUserCurrentRoles();

            $roles = Role::whereIn('name', $rolesNames)->get(['name', 'display_name', 'description'])->toArray();

            $this->line('ROLES');
            $this->table(['Id', 'Role', 'Display name', 'Description'], $roles);

            /**
             * 2. Permissions.
             */
            $permissionsNames = $user->getUserCurrentPermissions();

            $permissions = Permission::whereIn('name', $permissionsNames)->get(['name', 'display_name', 'description'])->toArray();

            $this->line('PERMISSIONS');
            $this->table(['Id', 'Permission', 'Display name', 'Description'], $permissions);

            /**
             * 3. Teams.
             */
            $teamsNames = $user->getUserCurrentTeams();

            $teams = Team::whereIn('name', $teamsNames)->get(['name', 'display_name', 'description'])->toArray();

            $this->line('TEAMS');
            $this->table(['Id', 'Team', 'Display name', 'Description'], $teams);
        }
    }
}
