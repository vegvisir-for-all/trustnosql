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

        while($keepAsking) {

            $roleName = $this->anticipate('Name of the role', $availableRoles);

            /**
             * $keepAsking should change only when role doesn't exist
             * It should NOT change when role exist (also, an error should be displayed)
             */

             if(($role = $this->getRole($roleName, true)) !== false)  {
                 $keepAsking = false;
             }
        }

        try {
            $userModel = Helper::getUserModel();

            $availableUsers = collect($userModel->all())->map(function ($item, $key) {
                return $item->email;
            })->toArray();

        } catch (\Exception $e) {
            // todo
        }

        $keepAsking = true;

        while($keepAsking) {
            $userEmails = $this->choice('E-mail address of the user to attach to', $availableUsers, null, count($availableUsers), true);
            $keepAsking = false;
        }



        try {

            foreach($userEmails as $key => $email) {
                $this->line(($key+1) . '/' . count($userEmails) . ". Attaching role '$roleName' to user '$email'...");
            }

            $this->successDeleting('role', $roleName);
        } catch (\Exception $e) {
            $this->errorDeleting('role', $roleName, $e->getMessage());
        }
    }
}
