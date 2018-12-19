<?php

namespace Vegvisir\TrustNoSql\Commands\Role;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Commands\BaseCommand;
use Vegvisir\TrustNoSql\Models\Role;

class Detach extends BaseCommand
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:role:detach';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Detach a TrustNoSql role from user(s)';

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

        $userModel = Helper::getUserModel();
        $userEmails = $this->getEntitiesList(new $userModel, 'E-mail address of the user to detach roles from');

        /**
         * We need to generate a list of roles assigned to chosen users
         */

        $rolesList = [];

        foreach($userEmails as $email) {
            $user = $this->getUser($email, true);

            $rolesList = array_merge($rolesList, $user->getUserCurrentRoles());
        }

        $rolesList = array_unique($rolesList);

        $roleNames = $this->getEntitiesList(new Role, 'Roles that should be detached', $rolesList);

        try {

            foreach($userEmails as $userKey => $email) {

                $this->line(($userKey+1) . '/' . count($userEmails) . ". Detaching roles from user '$email'...");

                $user = $this->getUser($email, true);

                foreach($roleNames as $roleKey => $roleName) {

                    $this->line('  ' . ($roleKey+1) . '/' . count($roleNames) . ". Detaching role '$roleName'...");

                    if(!$user->hasRole($roleName)) {

                        $this->error('    Didn\'t have a role. Skipping');
                        continue;

                    } else {

                        $this->line('    User had a role. Detaching...');

                        try {
                            $user->detachRole($roleName);
                            $this->info('    Role detached');
                        } catch (\Exception $e) {
                            $this->error('    Role not detached (' . $e->getMessage() . ')');
                        }

                    }

                    $this->successDetaching('role', $roleName, 'user', $email);
                }
            }

        } catch (\Exception $e) {
            $this->errorAttaching('role', 'multiple', 'user', $email, null, $e->getMessage());
        }

    }
}
