<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Commands\Team;

use Vegvisir\TrustNoSql\Commands\BaseCommand;
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Models\Role;
use Vegvisir\TrustNoSql\Models\Team;

class Info extends BaseCommand
{
    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:team:info';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Shows a detailed information about the team';

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
        $teamNames = $this->getEntitiesList(new Team());

        foreach ($teamNames as $teamName) {
            $this->line("Showing information for team '${teamName}'");

            $team = $this->getTeam($teamName, true);

            // 0. Info

            $this->line("Name: ${teamName}");
            $this->line("Display name: {$team->display_name}");
            $this->line("Description: {$team->description}");

            /**
             * 1. Roles.
             */
            $rolesNames = $team->getTeamCurrentRoles();

            $roles = Role::whereIn('name', $rolesNames)->get(['name', 'display_name', 'description'])->toArray();

            $this->line('ROLES');
            $this->table(['Id', 'Role', 'Display name', 'Description'], $roles);

            /**
             * 2. Users.
             */
            $usersEmails = $team->getTeamCurrentUsers();
            $userModel = Helper::getUserModel();

            $users = $userModel->whereIn('email', $usersEmails)->get(['name', 'email'])->toArray();

            $this->line('USERS');
            $this->table(['Id', 'Name', 'E-mail'], $users);
        }
    }
}
