<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Models\Team;
use Vegvisir\TrustNoSql\Traits\Commands\ErrorCommandTrait;
use Vegvisir\TrustNoSql\Traits\Commands\SuccessCommandTrait;

class BaseCommand extends Command
{
    use ErrorCommandTrait, SuccessCommandTrait;

    /**
     * Workaround for objects using old getUser, getTeam, etc. method names.
     *
     * @param mixed $name
     * @param mixed $arguments
     */
    public function __call($name, $arguments)
    {
        $functionNames = [
            'getPermission',
            'getRole',
            'getUser',
            'getTeam',
        ];

        if (!\in_array($name, $functionNames, true)) {
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
     * @param string $entityName      Name of the entity to be retrieved
     * @param bool   $shouldExist     Set to true, if entity should exist
     *
     * @return false|object
     */
    protected function getEntity($entityModelName, $entityName, $shouldExist)
    {
        $field = 'user' === $entityModelName ? 'email' : 'name';

        if ('user' === $entityModelName) {
            $entityModel = Helper::getUserModel();
        } else {
            $entityModelName = '\\Vegvisir\\TrustNoSql\\Models\\'.ucfirst($entityModelName);
            $entityModel = new $entityModelName();
        }

        $entity = $entityModel->where($field, $entityName)->first();

        if (null === $entity) {
            if ($shouldExist) {
                $this->doesNotExist($entityModelName, $entityName);

                return false;
            }

            return true;
        }
        if (!$shouldExist) {
            $this->alreadyExists($entityModelName, $entityName);

            return false;
        }

        return $entity;
    }

    /**
     * Function checks whether team functionality is set to on in the configs.
     * It can output an error message if current settings are undesirable.
     *
     * @param mixed $shouldBeOn
     */
    protected function isTeamFunctionalityOn($shouldBeOn)
    {
        // Checking if team functionality is on
        if (Config::get('trustnosql.teams.use_teams')) {
            if (!$shouldBeOn) {
                $this->noTeamFunctionality();

                return false;
            }

            return true;
        }

        if ($shouldBeOn) {
            $this->errorTeamFunctionality();

            return false;
        }

        return true;
    }

    /**
     * Displays entities list and returns array of choices.
     *
     * @param object|string $model    Model name or model itself
     * @param null|string   $question A question that should be asked
     * @param null|array    $roles    Optional array of roles to be displayed
     * @param null|mixed    $options
     *
     * @return array
     */
    protected function getEntitiesList($model, $question = null, $options = null)
    {
        if (!\is_object($model)) {
            $model = new $model();
        }

        $modelName = strtolower(class_basename($model));

        if (null === $question) {
            $question = ucfirst($modelName).' list';
        }

        $fieldName = function ($modelName) {
            return 'user' === $modelName ? 'email' : 'name';
        };

        if (null === $options) {
            $options = collect($model->all()->sortBy($fieldName($modelName)))->map(function ($item, $key) use ($modelName, $fieldName) {
                return $item[$fieldName($modelName)];
            })->toArray();
        }

        if (empty($options)) {
            $this->error('No '.$modelName.'s to choose. Sorry :(');
            die();
        }

        sort($options);

        return $this->choice($question, $options, null, \count($options), true);
    }
}
