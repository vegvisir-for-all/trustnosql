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
