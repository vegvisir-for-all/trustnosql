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
use Vegvisir\TrustNoSql\Models\Role;

class Detach extends BaseCommand
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:permission:detach';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Detach a TrustNoSql permission from role(s) or user(s)';

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

        $permissionNames = $this->getPermissionsList('Choose permission(s) you want to detach');

        $detachFromRoles = $this->confirm('Do you want to detach permissions from roles?', true);
        $detachFromUsers = $this->confirm('Do you want to detach permissions explicitely from users?', false);

        if(!$detachFromRoles && !$detachFromUsers) {
            $this->error('Sorry, can\'t help');
            return;
        }

        if($detachFromRoles) {
            $this->detachFromRoles($permissionNames);
        }

        if($detachFromUsers) {
            $this->detachFromUsers($permissionNames);
        }

    }

    protected function detachFromRoles($permissionNames)
    {

        $roleIds = [];

        foreach($permissionNames as $permissionName) {
            $roleIds = array_merge($roleIds, Permission::where('name', $permissionName)->first()->role_ids);
        }

        $availableRoles = collect(Role::whereIn('_id', $roleIds)->get(['name'])->toArray())->map(function ($item, $key) {
            return $item['name'];
        })->toArray();

        $roleNames = $this->getRolesList('Choose role(s) you want permission(s) to detach from', $availableRoles);

        try {

            foreach($roleNames as $roleName) {
                $this->line('Trying to detach permissions from role ' . $roleName);

                foreach($permissionNames as $permissionName) {

                    $this->line("Detaching permission '$permissionName'...");

                    $role = $this->getRole($roleName, true);

                    if(!$role->hasPermission($permissionName)) {
                        $this->line('Didn\'t have a permission. Skipping...');
                    } else {
                        $this->line('Had a permission. Detaching...');

                        try {
                            $role->detachPermission($permissionName);
                            $this->info('    Permission detached');
                        } catch (\Exception $e) {
                            $this->error('    Permission not detached (' . $e->getMessage() . ')');
                        }

                    }

                }
            }

        } catch (\Exception $e) {
            $this->error('    Permission not attached (' . $e->getMessage() . ')');
        }
    }

    protected function detachFromUsers($permissionNames)
    {
        $userEmails = $this->getUsersLIst('Choose user(s) you want permission(s) to attach to');

        // try {

            foreach($userEmails as $email) {
                $this->line('Trying to attach permissions to user ' . $email);

                foreach($permissionNames as $permissionName) {

                    $this->line("Attaching permission '$permissionName'...");

                    $user = $this->getUser($email, true);

                    if($user->hasPermission($permissionName)) {
                        $this->line('Already had. Skipping...');
                    } else {
                        $this->line('Didn\'t have a permission. Attaching...');

                        // try {
                            $user->attachPermission($permissionName);
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
