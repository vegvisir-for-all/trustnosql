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

class BaseCreate extends BaseCommand
{
    /**
     * Creates entity.
     *
     * @param object|string $model Model name or model itself
     *
     * @return mixed
     */
    public function entityCreate($model)
    {
        if (!\is_object($model)) {
            $model = new $model();
        }

        $modelName = strtolower(class_basename($model));

        $keepAsking = true;

        while ($keepAsking) {
            $entityName = $this->ask('Name of the '.$modelName);

            /*
             * $keepAsking should change only when team doesn't exist
             * It should NOT change when team exist (also, an error should be displayed)
             */

            if ($this->{'get'.ucfirst($modelName)}($entityName, false) === true) {
                $keepAsking = false;
            }
        }

        $displayName = $this->ask('Display name', false);
        $description = $this->ask('Description', false);

        try {
            $model->create([
                'name' => $entityName,
                'display_name' => $displayName,
                'description' => $description,
            ]);

            $this->successCreating($modelName, $entityName);
        } catch (\Exception $e) {
            $this->errorCreating($modelName, $entityName);
        }
    }
}
