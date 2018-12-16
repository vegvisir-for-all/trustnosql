<?php

namespace Vegvisir\TrustNoSql\Commands\Permission;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Commands\BaseCommand;
use Vegvisir\TrustNoSql\Models\Permission;

class Delete extends BaseCommand
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:permission:delete';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Delete a TrustNoSql permission';

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

        $availablePermissions = collect(Permission::all())->map(function ($item, $key) {
            return $item->name;
        })->toArray();

        while($keepAsking) {

            $permissionName = $this->anticipate('Name of the permission', $availablePermissions);

            /**
             * $keepAsking should change only when role doesn't exist
             * It should NOT change when role exist (also, an error should be displayed)
             */

             if(($permission = $this->getPermission($permissionName, true)) !== false)  {
                 $keepAsking = false;
             }
        }

        try {

            $permission->delete();

            $this->successDeleting('permission', $permissionName);
        } catch (\Exception $e) {
            $this->errorDeleting('permission', $permissionName, $e->getMessage());
        }
    }
}
