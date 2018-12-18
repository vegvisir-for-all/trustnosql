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
            $this->detachFromModel(new Role, $permissionNames);
        }

        if($detachFromUsers) {
            $this->detachFromModel(Helper::getUserModel(), $permissionNames);
        }

    }

    protected function detachFromModel($model, $permissionNames)
    {
        $entityIds = [];

        $modelName = strtolower(class_basename($model));

        foreach($permissionNames as $permissionName) {
            $entityIds = array_merge($entityIds, (array) Permission::where('name', $permissionName)->first()->{$modelName.'_ids'});
        }

        $fieldName = function ($modelName) {
            return (string) ($modelName == 'role' ? 'name' : 'email');
        };

        $availableEntities = collect($model->whereIn('_id', $entityIds)->get([$fieldName($modelName)])->toArray());

        $entitiesNames = $this->{'get' . ucfirst($modelName) . 'sList'}("Choose $modelName(s) you want permission(s) to detach from", $availableEntities);

        try {

            foreach($entitiesNames as $entityName) {
                $this->line("Trying to detach permissions from $modelName " . $entityName);

                foreach($permissionNames as $permissionName) {

                    $this->line("Detaching permission '$permissionName'...");

                    $entity = $this->{'get' . ucfirst($modelName)}($entityName, true);

                    if(is_bool($entity)) {
                        dd($entity, $modelName, $entityName);
                    }

                    if(!$entity->hasPermission($permissionName)) {
                        $this->line('Didn\'t have a permission. Skipping...');
                    } else {
                        $this->line('Had a permission. Detaching...');

                        try {
                            $entity->detachPermission($permissionName);
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
}
