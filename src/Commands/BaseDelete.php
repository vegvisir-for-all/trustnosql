<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Commands;

class BaseDelete extends BaseCommand
{
    /**
     * Deletes entity.
     *
     * @param object|string $model Model name or model itself
     *
     * @return mixed
     */
    public function entityDelete($model)
    {
        if (!\is_object($model)) {
            $model = new $model();
        }

        $modelName = strtolower(class_basename($model));

        $availableEntities = collect($model->all())->map(function ($item, $key) {
            return $item->name;
        })->toArray();

        $entitiesNames = $this->getEntitiesList($model, "Choose ${modelName}(s) you want to delete", $availableEntities);

        try {
            foreach ($entitiesNames as $entityName) {
                $this->{'get'.ucfirst($modelName)}($entityName, true)->delete();
                $this->successDeleting($modelName, $entityName);
            }
        } catch (\Exception $e) {
            $this->errorDeleting($modelName, $teamName);
        }
    }
}
