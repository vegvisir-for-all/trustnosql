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

class Attach extends BaseCommand
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:role:attach';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Attach a TrustNoSql role to user(s)';

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

        $availableRoles = collect(Role::all())->map(function ($item, $key) {
            return $item->name;
        })->toArray();

        /**
         * Displaying menu with roles
         */

        $roleNames = $this->choice('Choose role(s) you want to attach', $availableRoles, null, count($availableRoles), true);

        try {
            $userModel = Helper::getUserModel();

            $availableUsers = collect($userModel->all())->map(function ($item, $key) {
                return $item->email;
            })->toArray();

        } catch (\Exception $e) {
            // todo
        }

        $userEmails = $this->choice('E-mail address of the user to attach to', $availableUsers, null, count($availableUsers), true);

        try {

            foreach($userEmails as $userKey => $email) {
                $this->line(($userKey+1) . '/' . count($userEmails) . ". Attaching roles to user '$email'...");

                // Try to attach roles to user

                $user = $this->getUser($email, true);

                foreach($roleNames as $roleKey => $roleName) {
                    $this->line('  ' . ($roleKey+1) . '/' . count($roleNames) . ". Attaching role '$roleName'...");

                    if($user->hasRole($roleName)) {
                        $this->error('    Already has. Skipping');
                        continue;
                    } else {
                        $this->line('    User didn\'t have a role. Attaching');

                        try {
                            $user->attachRole($roleName);
                            $this->info('    Role attached');
                        } catch (\Exception $e) {
                            $this->error('    Role not attached (' . $e->getMessage() . ')');
                        }
                    }
                }
            }

            $this->successDeleting('role', $roleName);
        } catch (\Exception $e) {
            $this->errorDeleting('role', $roleName, $e->getMessage());
        }
    }
}
