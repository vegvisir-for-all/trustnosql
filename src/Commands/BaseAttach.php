<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * (c) Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 *
 * This source file is subject to the GPL-3.0-or-later license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vegvisir\TrustNoSql\Commands;

use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Models\Role;
use Vegvisir\TrustNoSql\Models\Team;

class BaseAttach extends BaseCommand
{
    /**
     * Attach $model to possible entities.
     *
     * @param object $model    Model being attached
     * @param array  $askAbout Model names to attach $model to
     *
     * @return mixed
     */
    protected function entityAttach($model, $askAbout)
    {
        if (!\is_object($model)) {
            $model = new $model();
        }

        $modelName = strtolower(class_basename($model));

        $entityNames = $this->getEntitiesList($model, "Choose ${modelName}(s) you want to attach");

        if (\in_array('roles', $askAbout, true)) {
            $this->attachToModel(new Role(), $model, $entityNames);
        }

        if (\in_array('teams', $askAbout, true)) {
            $this->attachToModel(new Team(), $model, $entityNames);
        }

        if (\in_array('users', $askAbout, true)) {
            $this->attachToModel(Helper::getUserModel(), $model, $entityNames);
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
    protected function attachToModel($model, $entityModel, $entityNames)
    {
        if (!\is_object($model)) {
            $model = new $model();
        }

        $modelName = strtolower(class_basename($model));

        $entityModelName = strtolower(class_basename($entityModel));

        $entityToAttachToNames = $this->getEntitiesList($model, ucfirst(str_plural($modelName)).' to attach to');

        try {
            foreach ($entityToAttachToNames as $entityToAttachToName) {
                $this->line('Trying to attach '.str_plural($entityModelName)." to ${modelName} ".$entityToAttachToName);

                foreach ($entityNames as $entityAttachedName) {
                    $this->line("Attaching ${entityModelName} '${entityAttachedName}'...");

                    $entity = $this->{'get'.ucfirst($modelName)}($entityToAttachToName, true);

                    if ($entity->{'has'.ucfirst($entityModelName)}($entityAttachedName)) {
                        $this->line('Already had. Skipping...');
                    } else {
                        $this->line("Didn't have a ${entityModelName} ${entityAttachedName}. Attaching...");

                        try {
                            $entity->{'attach'.ucfirst($entityModelName)}($entityAttachedName);
                            $this->info('    '.ucfirst(str_plural($entityModelName)).' attached');
                        } catch (\Exception $e) {
                            $this->error('    '.ucfirst(str_plural($entityModelName)).' not attached');
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            $this->error('    '.ucfirst(str_plural($entityModelName)).' not attached');
        }
    }
}
