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

class Create extends BaseCommand
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:permission:create';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Create a new TrustNoSql permission';

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

        while($keepAsking) {
            $permissionName = $this->ask('Name of the permission');

            /**
             * $keepAsking should change only when permission doesn't exist
             * It should NOT change when permission exists (also, an error should be displayed)
             */

             if($this->getPermission($permissionName, false) == true) {
                 $keepAsking = false;
             }

             if(Helper::isPermissionWildcard($permissionName)) {
                 $this->error('Wildcard permissions are not allowed here');

                 $keepAsking = true;
             }
        }

        $displayName = $this->ask('Display name', false);
        $description = $this->ask('Description', false);

        try {
            Permission::create([
                'name' => $permissionName,
                'display_name' => $displayName,
                'description' => $description
            ]);

            $this->successCreating('permission', $permissionName);
        } catch (\Exception $e) {
            $this->errorCreating('permission', $permissionName, $e->getMessage());
        }
    }
}
