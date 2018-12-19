<?php

namespace Vegvisir\TrustNoSql\Traits;

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

    protected $namespaces = [
        'has',
        'attach',
        'detach',
        'sync'
    ];

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

    public static function boot()
    {
        parent::boot();

        static::bootModelTrait();
        static::bootTrustNoSqlEvents();
    }

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

    protected function getModelCurrentEntities($entityModelName, $namespace = null, $forceNoCache = false)
    {

        /**
         * If TrustNoSql uses cache, this should be retrieved by roleCachedPermissions, provided
         * by RoleCacheableTrait
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

        return collect($entityCollection->get())->map(function ($item, $key) use ($field) {
            return $item->$field;
        })->toArray();

    }

    protected function hasEntities($entityModelName, $entityList, $requireAll)
    {
        $functionName = 'current' . ucfirst(class_basename($this)) . 'Has' . ucfirst(str_plural(($entityModelName)));
        return $this->modelChecker()->$functionName($entityList, $requireAll);
    }

    public function syncEntities($entityModelName, $permissions)
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
