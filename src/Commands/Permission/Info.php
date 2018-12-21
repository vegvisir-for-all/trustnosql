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

namespace Vegvisir\TrustNoSql\Commands\Permission;

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
    protected $signature = 'trustnosql:permission:info';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Shows a detailed information about the permission';

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
        $permissionNames = $this->getEntitiesList(new Permission());

        foreach ($permissionNames as $permissionName) {
            $this->line("Showing information for permission '${permissionName}'");

            $permission = $this->getPermission($permissionName, true);

            // 0. Info

            $this->line("Name: ${permissionName}");
            $this->line("Display name: {$permission->display_name}");
            $this->line("Description: {$permission->description}");

            /**
             * 1. Permissions.
             */
            $rolesNames = $permission->getPermissionCurrentRoles();

            $roles = Role::whereIn('name', $rolesNames)->get(['name', 'display_name', 'description'])->toArray();

            $this->line('ROLES');
            $this->table(['Id', 'Role', 'Display name', 'Description'], $roles);

            /**
             * 1. Users.
             */
            $users = $permission->users()->get(['name', 'email'])->toArray();

            $this->line('USERS');
            $this->table(['Id', 'Name', 'E-mail'], $users);
        }
    }
}
