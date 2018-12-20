<?php

namespace Vegvisir\TrustNoSql\Traits;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Exceptions\Entity\AttachEntitiesException;
use Vegvisir\TrustNoSql\Exceptions\Entity\DetachEntitiesException;
use Vegvisir\TrustNoSql\Exceptions\Entity\DeleteEntitiesException;
use Vegvisir\TrustNoSql\Exceptions\Entity\SyncEntitiesException;
use Vegvisir\TrustNoSql\Checkers\CheckProxy;

trait ModelTrait
{

    /**
     * Namespaces for checking functions
     *
     * @var array
     */
    protected $namespaces = [
        'has',
        'attach',
        'detach',
        'sync'
    ];

    /**
     * Available entities
     *
     * @var array
     */
    protected $entities = [
        'permissions',
        'roles',
        'teams'
    ];

    public function __call($name, $arguments)
    {

        $originalName = $name;

        $name = explode('_', snake_case($name));

        if(in_array($name[0], $this->namespaces) && in_array(str_plural($name[1]), $this->entities)) {
            return $this->{$name[0] . 'Entities'}($name[1], $arguments[0], isset($arguments[1]) ? $arguments[1] : false);
        } elseif ($name[0] == 'get' && in_array($name[2], ['current', 'cached'])) {
            return $this->getModelCurrentEntities(strtolower(str_plural($name[3])));
        }

        return parent::__call($originalName, $arguments);
    }

    /**
     * Boots trait:
     * 1. flushes cache on deletion and saving
     * 2. removing of attached ids in case of deletion
     * 3. Register event listeners
     */
    public static function boot()
    {
        parent::boot();

        static::bootModelTrait();
        static::bootTrustNoSqlEvents();
    }

    /**
     * Boots trait:
     * 1. flushes cache on deletion and saving
     * 2. removing of attached ids in case of deletion
     */
    protected static function bootModelTrait()
    {
        $flushCache = function ($model) {
            $model->currentModelFlushCache();
        };

        static::deleted($flushCache);
        static::saved($flushCache);

        if(method_exists(static::class, 'restored')) {
            static::restored($flushCache);
        }

        static::deleting(function ($model) {

            /**
             * The objective is to remove all deleted model keys from collections.
             * It'd be quicker to execute it with a MongoDB query, not Moloquent builder
             */
            $modelName = strtolower(class_basename($model));
            $modelKeysArrayFieldName = $modelName . '_ids';

            $entityModels = ['permission', 'role', 'user', 'trait'];

            foreach($entityModels as $entityModel) {

                if(!method_exists(get_class($model), str_plural($entityModel))) {
                    // no relation
                    continue;
                }

                try {
                    DB::collection(Config::get('trustnosql.collections.' . str_plural($entityModel, str_plural($entityModel))))
                        ->where($modelKeysArrayFieldName, $model->id)->pull($modelKeysArrayFieldName, $model->id);
                } catch (\Exception $e) {
                    throw new DeleteEntitiesException($modelName, $model->name);
                }
            }

        });
    }

    /**
     * Returns the right checker for the User model.
     *
     * @return \Vegvisir\TrustNoSql\Checkers\User\UserChecker;
     */
    protected function modelChecker()
    {
        return (new CheckProxy($this))->getChecker();
    }

    /**
     * Returns cached or db-generated list of current model's entities
     *
     * @param string $entityModelName Name of the model
     * @param string|null $namespace Permission namespace
     * @param bool $forceNoCache If set to true, method will not use cached data
     * @return array
     */
    protected function getModelCurrentEntities($entityModelName, $namespace = null, $forceNoCache = false)
    {

        /**
         * If TrustNoSql uses cache and $forceNoCache is false, this should be retrieved from cache
         */
        if(!$forceNoCache && Config::get('trustnosql.cache.use_cache')) {
            return $this->{str_replace('Current', 'Cached', __FUNCTION__)}($entityModelName, $namespace);
        }

        /**
         * Otherwise, retrieve a list of current entities from the DB
         */
        $entityCollection = $this->{strtolower(str_plural($entityModelName))}();

        if($namespace !== null) {
            $entityCollection = $entityCollection->where('name', 'like', $namespace . '/%');
        }

        $field = $entityModelName == 'users' ? 'email' : 'name';

        $entityCollection->sortBy($field);

        return collect($entityCollection->get())->map(function ($item, $key) use ($field) {
            return $item->$field;
        })->toArray();

    }

    /**
     * Checks whether model has entities.
     *
     * @param string $entityModelName Name of the model
     * @param string|array $entityList Array of entity names or comma-separated string
     * @param bool $requireAll If set to true, all entities must be attached to model
     * @return bool
     */
    protected function hasEntities($entityModelName, $entityList, $requireAll)
    {

        if($entityModelName == 'team' && !Config::get('trustnosql.teams.use_teams', true)) {
            return true;
        }

        $functionName = 'current' . ucfirst(class_basename($this)) . 'Has' . ucfirst(str_plural(($entityModelName)));
        return $this->modelChecker()->$functionName($entityList, $requireAll);
    }

    /**
     * Sync entities with model.
     *
     * @param string $entityModelName Name of the model
     * @param string|array $entityList Array of entity names or comma-separated string
     * @return object
     */
    public function syncEntities($entityModelName, $entityList)
    {
        $entitiesKeys = Helper::{'get' . ucfirst($entityModelName) . 'Keys'}($entityList);

        try {
            $changes = $this->{strtolower(str_plural($entityModelName))}()->sync($entitiesKeys);

            $this->currentModelFlushCache(strtolower(str_plural($entityModelName)));
            $this->fireTrustNoSqlEvent(strtolower(str_plural($entityModelName)) . '.synced', [$this, $changes]);
        } catch (\Exception $e) {
            $this->fireTrustNoSqlEvent(strtolower(str_plural($entityModelName)) . '.not-synced', [$this, $changes]);
            throw new SyncEntitiesException($entityModelName);
        }

        return $this;
    }

    /**
     * Attach entities to model.
     *
     * @param string $entityModelName Name of the model
     * @param string|array $entityList Array of entity names or comma-separated string
     * @return object
     */
    protected function attachEntities($entityModelName, $entityList)
    {

        $entitiesKeys = Helper::{'get' . ucfirst($entityModelName) . 'Keys'}($entityList);

        try {
            $this->{strtolower(str_plural($entityModelName))}()->attach($entitiesKeys);

            $this->currentModelFlushCache(strtolower(str_plural($entityModelName)));
            $this->fireTrustNoSqlEvent(strtolower(str_plural($entityModelName)) . '.attached', [$this, $entitiesKeys]);
        } catch (\Exception $e) {
            $this->fireTrustNoSqlEvent(strtolower(str_plural($entityModelName)) . '.not-attached', [$this, $entityList]);
            throw new AttachEntitiesException($entityModelName);
        }

        return $this;
    }

    /**
     * Detach entities from model.
     *
     * @param string $entityModelName Name of the model
     * @param string|array $entityList Array of entity names or comma-separated string
     * @return object
     */
    public function detachEntities($entityModelName, $entityList)
    {

        $entitiesKeys = Helper::{'get' . ucfirst($entityModelName) . 'Keys'}($entityList);

        try {
            $this->{strtolower(str_plural($entityModelName))}()->detach($entitiesKeys);

            $this->currentModelFlushCache(strtolower(str_plural($entityModelName)));
            $this->fireTrustNoSqlEvent(strtolower(str_plural($entityModelName)) . '.detached', [$this, $entityList]);
        } catch (\Exception $e) {
            $this->fireTrustNoSqlEvent(strtolower(str_plural($entityModelName)) . '.not-detached', [$this, $entityList]);
            throw new DetachEntitiesException($entityModelName);
        }

        return $this;
    }

}
