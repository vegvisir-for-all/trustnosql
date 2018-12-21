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
            $this->errorDeleting($modelName, $teamName, $e->getMessage());
        }
    }
}
