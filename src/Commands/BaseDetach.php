<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Commands;

use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Models\Role;
use Vegvisir\TrustNoSql\Models\Team;

class BaseDetach extends BaseCommand
{
    /**
     * Attach $model to possible entities.
     *
     * @param object $model    Model being attached
     * @param array  $askAbout Model names to attach $model to
     *
     * @return mixed
     */
    protected function entityDetach($model, $askAbout)
    {
        if (!\is_object($model)) {
            $model = new $model();
        }

        $modelName = strtolower(class_basename($model));

        $entityNames = $this->getEntitiesList($model, "Choose ${modelName}(s) you want to detach");

        if (\in_array('roles', $askAbout, true)) {
            $this->detachFromModel($model, $entityNames, new Role());
        }

        if (\in_array('teams', $askAbout, true)) {
            $this->detachFromModel($model, $entityNames, new Team());
        }

        if (\in_array('users', $askAbout, true)) {
            $this->detachFromModel($model, $entityNames, Helper::getUserModel());
        }
    }

    /**
     * Attaching entityNames (of type specified in entityModel) to model.
     * Function outputs menu of choices from $model.
     *
     * @param object $model       Model to attach to
     * @param object $entityModel Model being attached
     * @param array  $entityNames Names of entities being attached to $model
     *
     * @return mixed
     */
    protected function detachFromModel($entityModel, $entityNames, $model)
    {
        if (!\is_object($model)) {
            $model = new $model();
        }

        $modelName = strtolower(class_basename($model));

        $entityModelName = strtolower(class_basename($entityModel));

        $availableEntities = [];

        foreach ($entityNames as $entityName) {
            $entity = $this->{'get'.ucfirst($entityModelName)}($entityName, true);
            $currentModelEntities = $entity->{'getModelCurrent'.ucfirst(str_plural($modelName))}();

            $availableEntities = array_merge($availableEntities, $currentModelEntities);
        }

        $availableEntities = array_unique($availableEntities);
        sort($availableEntities);

        $entityToDetachFromNames = $this->getEntitiesList($model, ucfirst(str_plural($modelName)).' to detach from', $availableEntities);

        try {
            foreach ($entityToDetachFromNames as $entityToDetachFromName) {
                $this->line('Trying to detach '.str_plural($entityModelName)." from ${modelName} ".$entityToDetachFromName);

                foreach ($entityNames as $entityDetachedName) {
                    $this->line("Detaching '${entityDetachedName}'...");

                    $entity = $this->{'get'.ucfirst($modelName)}($entityToDetachFromName, true);

                    if (!$entity->{'has'.ucfirst($entityModelName)}($entityDetachedName)) {
                        $this->line('Didn\'t have. Skipping...');
                    } else {
                        $this->line("Had a ${entityModelName} ${entityDetachedName}. Detaching...");

                        try {
                            $entity->{'detach'.ucfirst($entityModelName)}($entityDetachedName);
                            $this->info('    '.ucfirst($entityModelName).' detached');
                        } catch (\Exception $e) {
                            $this->error('    '.ucfirst($entityModelName).' not detached');
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->error('    '.ucfirst(str_plural($entityModelName)).' not attached');
        }
    }
}
