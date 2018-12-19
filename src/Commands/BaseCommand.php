<?php

namespace Vegvisir\TrustNoSql\Commands;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Models\Permission;
use Vegvisir\TrustNoSql\Models\Role;
use Vegvisir\TrustNoSql\Models\Team;
use Vegvisir\TrustNoSql\Traits\Commands\ErrorCommandTrait;
use Vegvisir\TrustNoSql\Traits\Commands\SuccessCommandTrait;

class BaseCommand extends Command
{

    use ErrorCommandTrait, SuccessCommandTrait;

    /**
     * Workaround for objects using old getUser, getTeam, etc. method names
     */
    public function __call($name, $arguments)
    {
        $functionNames = [
            'getPermission',
            'getRole',
            'getUser',
            'getTeam'
        ];

        if(!in_array($name, $functionNames)) {
            return parent::__call($name, $arguments);
        }

        $entityModelName = strtolower(substr($name, 3));

        return $this->getEntity($entityModelName, $arguments[0], $arguments[1]);
    }

    /**
     * Get entity. Replaced old getUser, getTeam, etc.
     * Returns entity or bool. Outputs console message.
     *
     * @param string $entityModelName Name of the model to retrieve
     * @param string $entityName Name of the entity to be retrieved
     * @param bool $shouldExist Set to true, if entity should exist
     * @return Object|false
     */
    protected function getEntity($entityModelName, $entityName, $shouldExist)
    {

        $field = $entityModelName == 'user' ? 'email' : 'name';

        if($entityModelName == 'user') {
            $entityModel = Helper::getUserModel();
        } else {
            $entityModelName = "\\Vegvisir\\TrustNoSql\\Models\\" . ucfirst($entityModelName);
            $entityModel = new $entityModelName;
        }

        $entity = $entityModel->where($field, $entityName)->first();

        if($entity == null) {
            if($shouldExist) {
                $this->doesNotExist($entityModelName, $entityName);
                return false;
            } else {
                return true;
            }
        } elseif(!$shouldExist) {
            $this->alreadyExists($entityModelName, $entityName);
            return false;
        }

        return $entity;
    }

    /**
     * Function checks whether team functionality is set to on in the configs.
     * It can output an error message if current settings are undesirable.
     */
    protected function isTeamFunctionalityOn($shouldBeOn)
    {
        // Checking if team functionality is on
        if (Config::get('trustnosql.teams.use_teams')) {

            if(!$shouldBeOn) {
                $this->noTeamFunctionality();
                return false;
            }

            return true;

        } else {

            if($shouldBeOn) {
                $this->errorTeamFunctionality();
                return false;
            }

            return true;

        }
    }

    /**
     * Displays entities list and returns array of choices.
     *
     * @param string|object $model Model name or model itself
     * @param string|null $question A question that should be asked
     * @param array|null $roles Optional array of roles to be displayed
     * @return array
     */
    protected function getEntitiesList($model, $question = null, $options = null)
    {

        if(!\is_object($model)) {
            $model = new $model;
        }

        $modelName = strtolower(class_basename($model));

        if($question == null) {
            $question = ucfirst($modelName) . ' list';
        }

        $fieldName = function ($modelName) {
            return $modelName == 'user' ? 'email' : 'name';
        };

        if($options == null) {
            $options = collect($model->all()->sortBy($fieldName($modelName)))->map(function ($item, $key) use($modelName, $fieldName) {
                return $item[$fieldName($modelName)];
            })->toArray();
        }

        if(empty($options)) {
            $this->error('No ' . $modelName . 's to choose. Sorry :(');
            die();
        }

        sort($options);

        $optionNames = $this->choice($question, $options, null, count($options), true);

        return $optionNames;
    }

}
