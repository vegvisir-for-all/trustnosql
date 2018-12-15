<?php

namespace Vegvisir\TrustNoSql\Commands\Role;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Commands\BaseCommand;
use Vegvisir\TrustNoSql\Models\Role;

class Delete extends BaseCommand
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:role:delete';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Delete a TrustNoSql role';

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

            $role->delete();

            $this->successDeleting('role', $roleName);
        } catch (\Exception $e) {
            $this->errorDeleting('role', $roleName, $e->getMessage());
        }
    }
}
