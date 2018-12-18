<?php

namespace Vegvisir\TrustNoSql\Commands;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Commands\BaseCommand;

class BaseDelete extends BaseCommand
{

    /**
     * Deletes entity.
     *
     * @param string|object $model Model name or model itself
     * @return mixed
     */
    public function entityDelete($model)
    {

        if(!\is_object($model)) {
            $model = new $model;
        }

        $modelName = strtolower(class_basename($model));

        $availableEntities = collect($model->all())->map(function ($item, $key) {
            return $item->name;
        })->toArray();

        $entitiesNames = $this->getEntitiesList($model, "Choose $modelName(s) you want to delete", $availableEntities);

        try {

            foreach($entitiesNames as $entityName) {
                $this->{'get' . ucfirst($modelName)}($entityName, true)->delete();
                $this->successDeleting($modelName, $entityName);
            }

        } catch (\Exception $e) {
            $this->errorDeleting($modelName, $teamName, $e->getMessage());
        }

    }

}
