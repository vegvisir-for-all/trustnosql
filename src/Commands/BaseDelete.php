<?php

namespace Vegvisir\TrustNoSql\Commands;

use Vegvisir\TrustNoSql\Commands\BaseCommand;

class BaseDelete extends BaseCommand
{

    public function entityDelete($model)
    {

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
