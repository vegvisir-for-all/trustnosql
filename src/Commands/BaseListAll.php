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

class BaseListAll extends BaseCommand
{
    /**
     * Lists all entities.
     *
     * @param object|string $model Model name or model itself
     *
     * @return mixed
     */
    public function entitiesListAll($model)
    {
        if (!\is_object($model)) {
            $model = new $model();
        }

        $modelName = strtolower(class_basename($model));

        $roles = collect($model->all([
            'id', 'name', 'display_name', 'description',
        ]))->toArray();

        $headers = [
            '_id', 'Name', 'Display name', 'Description',
        ];

        $this->table($headers, $roles);
    }
}
