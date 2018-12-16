<?php

namespace Vegvisir\TrustNoSql\Commands\Permission;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Commands\BaseCommand;
use Vegvisir\TrustNoSql\Models\Permission;

class Attach extends BaseCommand
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:permission:attach';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Attach a TrustNoSql role to role(s)';

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

        $keepAsking = true;

        $permissionNames = $this->getPermissionsList('Choose permission(s) you want to attach');

        $attachToRoles = $this->confirm('Do you want to attach permissions to roles?', true);
        $attachToUsers = $this->confirm('Do you want to attach permissions explicitely to users?', false);

        if(!$attachToRoles && !$attachToUsers) {
            $this->error('Sorry, can\'t help');
            return;
        }

        if($attachToRoles) {
            $this->attachToRoles($permissionNames);
        }

    }

    protected function attachToRoles($permissionNames)
    {
        $roleNames = $this->getRolesLIst('Choose role(s) you want permission(s) to attach to');

        // try {

            foreach($roleNames as $roleName) {
                $this->line('Trying to attach permissions to role ' . $roleName);

                foreach($permissionNames as $permissionName) {

                    $this->line("Attaching permission '$permissionName'...");

                    $role = $this->getRole($roleName, true);

                    if($role->hasPermission($permissionName)) {
                        $this->line('Already had. Skipping...');
                    } else {
                        $this->line('Didn\'t have a permission. Attaching...');

                        // try {
                            $role->attachPermission($permissionName);
                            $this->info('    Permission attached');
                        // } catch (\Exception $e) {
                        //     $this->error('    Permission not attached (' . $e->getMessage() . ')');
                        // }

                    }

                }
            }

        // } catch (\Exception $e) {
        //     $this->error('    Permission not attached (' . $e->getMessage() . ')');
        // }
    }
}
