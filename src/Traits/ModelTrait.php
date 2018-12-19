<?php

namespace Vegvisir\TrustNoSql\Traits;

use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Helper;
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
            return $this->hasEntities($name[1], $arguments[0], isset($arguments[1]) ? $arguments[1] : false);
        }

        if($name[0] == 'get' && in_array($name[2], ['current', 'cached'])) {
            return $this->getModelCurrentEntities(strtolower(str_plural($name[3])));
        }

        return parent::__call($originalName, $arguments);
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

    protected function getModelCurrentEntities($entityModelName, $namespace = null)
    {

        /**
         * If TrustNoSql uses cache, this should be retrieved by roleCachedPermissions, provided
         * by RoleCacheableTrait
         */
        if(Config::get('trustnosql.cache.use_cache')) {
            return $this->{str_replace('Current', 'Cached', __FUNCTION__)}($entityModelName, $namespace);
        }

        /**
         * Otherwise, retrieve a list of current entities from the DB
         */
        $entityCollection = $this->{strtolower(str_plural($entityModelName))}();

        if($namespace !== null) {
            $entityCollection = $entityCollection->where('name', 'like', $namespace . '/%');
        }

        $field = Helper::isUser($this) ? 'email' : 'name';

        return collect($entityCollection->get())->map(function ($item, $key) use ($field) {
            return $item->$field;
        })->toArray();

    }

    protected function hasEntities($entityModelName, $entityList, $requireAll)
    {

        $functionName = 'current' . ucfirst(class_basename($this)) . 'Has' . ucfirst(str_plural(($entityModelName)));
        return $this->modelChecker()->$functionName($entityList, $requireAll);
    }

}
