<?php

namespace Vegvisir\TrustNoSql\Commands;

use Vegvisir\TrustNoSql\Commands\BaseCommand;

class BaseListAll extends BaseCommand
{

    public function entitiesListAll($model)
    {

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
