<?php

namespace Vegvisir\TrustNoSql\Commands;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Commands\BaseCommand;

class BaseListAll extends BaseCommand
{

    /**
     * Lists all entities
     *
     * @param string|object $model Model name or model itself
     * @return mixed
     */
    public function entitiesListAll($model)
    {

        if(!\is_object($model)) {
            $model = new $model;
        }

        $modelName = strtolower(class_basename($model));

        $roles = collect($model->all([
            'id', 'name', 'display_name', 'description'
        ]))->toArray();

        $headers = [
            '_id', 'Name', 'Display name', 'Description'
        ];

        $this->table($headers, $roles);
    }

}
