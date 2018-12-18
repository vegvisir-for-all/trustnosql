<?php

namespace Vegvisir\TrustNoSql\Commands;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Commands\BaseCommand;

class BaseCreate extends BaseCommand
{

    /**
     * Creates entity.
     *
     * @param string|object $model Model name or model itself
     * @return mixed
     */
    public function entityCreate($model)
    {

        if(!\is_object($model)) {
            $model = new $model;
        }

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
