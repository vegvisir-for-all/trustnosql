<?php

namespace Vegvisir\TrustNoSql\Traits\Cacheable;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

trait ModelCacheableTrait
{

    /**
     * Flush cache for given model (optionally, an array of entities to be flushed,
     * can be passed).
     *
     * @param array|null $entityModelNames
     * @return void
     */
    public function currentModelFlushCache($entityModelNames = null)
    {

        if(null == $entityModelNames) {
            $entityModelNames = [
                'permission',
                'role',
                'user',
                'team'
            ];
        }

        if(!is_array($entityModelNames)) {
            $entityModelNames = explode(',', $entityModelNames);
        }

        foreach($entityModelNames as $entityModelName) {
            try {
                $cacheKey = 'trustnosql_'
                    . strtolower(str_plural($entityModelName))
                    . '_for_'
                    . strtolower(class_basename($this))
                    . '_'
                    . $this->id;

                Cache::forget($cacheKey);

            } catch (\Exception $e) {}
        }
    }

    /**
     * Gets a list of cached model entities' names or stores it to cache, if it wasn't
     * present before.
     *
     * @param string $entityModelName
     * @param string|null $namespace
     * @return array
     */
    protected function getModelCachedEntities($entityModelName, $namespace = null)
    {

        $cacheKey = 'trustnosql_'
            . strtolower(str_plural($entityModelName))
            . '_for_'
            . strtolower(class_basename($this))
            . '_'
            . $this->id;

        if($namespace !== null) {
            $cacheKey .= '_' . $namespace;
        }

        return Cache::remember($cacheKey, Config::get('cache.ttl', 60), function () use ($entityModelName, $namespace) {
            return $this->getModelCurrentEntities($entityModelName, $namespace, true);
        });

    }

}
