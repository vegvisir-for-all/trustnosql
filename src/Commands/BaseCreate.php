<?php

namespace Vegvisir\TrustNoSql\Commands;

use Vegvisir\TrustNoSql\Commands\BaseCommand;

class BaseCreate extends BaseCommand
{

    public function entityCreate($model)
    {

        $modelName = strtolower(class_basename($model));

        $keepAsking = true;

        while($keepAsking) {
            $entityName = $this->ask('Name of the ' . $modelName);

            /**
             * $keepAsking should change only when team doesn't exist
             * It should NOT change when team exist (also, an error should be displayed)
             */

             if($this->{'get' . ucfirst($modelName)}($entityName, false) == true) {
                 $keepAsking = false;
             }
        }

        $displayName = $this->ask('Display name', false);
        $description = $this->ask('Description', false);

        try {
            $model->create([
                'name' => $entityName,
                'display_name' => $displayName,
                'description' => $description
            ]);

            $this->successCreating($modelName, $entityName);
        } catch (\Exception $e) {
            $this->errorCreating($modelName, $entityName, $e->getMessage());
        }

    }

}
