<?php

namespace Vegvisir\TrustNoSql\Commands;

use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Commands\BaseCommand;

class BaseAttach extends BaseCommand
{

    /**
     * Attach $model to possible entities.
     *
     * @param Object $model Model being attached
     * @param array $askAbout Model names to attach $model to
     * @return mixed
     */
    protected function entityAttach($model, $askAbout)
    {

        if(!\is_object($model)) {
            $model = new $model;
        }

        $modelName = strtolower(class_basename($model));

        $keepAsking = true;

        $availableEntities = collect($model->all())->map(function ($item, $key) {
            return $item->name;
        })->toArray();

        $entityNames = $this->getEntitiesList($model, "Choose $modelName(s) you want to attach");

        if(in_array('roles', $askAbout)) {
            $this->attachToModel(new Role, $model, $entityNames);
        }

        if(in_array('teams', $askAbout)) {
            $this->attachToModel(new Team, $model, $entityNames);
        }

        if(in_array('users', $askAbout)) {
            $this->attachToModel(Helper::getUserModel(), $model, $entityNames);
        }
    }

    /**
     * Attaching entityNames (of type specified in entityModel) to model.
     * Function outputs menu of choices from $model
     *
     * @param Object $model Model to attach to
     * @param Object $entityModel Model being attached
     * @param array $entityNames Names of entities being attached to $model
     * @return mixed
     */
    protected function attachToModel($model, $entityModel, $entityNames)
    {

        if(!\is_object($model)) {
            $model = new $model;
        }

        $modelName = strtolower(class_basename($model));

        $entityModelName = strtolower(class_basename($entityModel));

        $entityToAttachToNames = $this->getEntitiesList($model, ucfirst(str_plural($modelName)) . ' to attach to');

        try {

            foreach($entityToAttachToNames as $entityToAttachToName) {
                $this->line("Trying to attach permissions to $modelName " . $entityToAttachToName);

                foreach($entityNames as $entityAttachedName) {

                    $this->line("Attaching $entityModelName '$entityAttachedName'...");

                    $entity = $this->{'get' . ucfirst($modelName)}($entityToAttachToName, true);

                    if($entity->{'has' . ucfirst($entityModelName)}($entityAttachedName)) {
                        $this->line('Already had. Skipping...');
                    } else {
                        $this->line("Didn't have a $entityModelName. Attaching...");

                        try {
                            $entity->attachPermission($permissionName);
                            $this->info('    Permission attached');
                        } catch (\Exception $e) {
                            $this->error('    Permission not attached (' . $e->getMessage() . ')');
                        }

                    }

                }
            }

        } catch (\Exception $e) {
            $this->error('    Permission not attached (' . $e->getMessage() . ')');
        }
    }

}
