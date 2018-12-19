<?php

namespace Vegvisir\TrustNoSql\Traits\Cacheable;

use Illuminate\Support\Facades\Cache;

trait ModelCacheableTrait {


    protected function currentModelFlushCache($entityModelNames = null)
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

    protected function getModelCachedEntities($entityModelName, $namespace = null)
    {

        /**
         * Cache key generate
         */
        $cacheKey = 'trustnosql_'
            . strtolower(str_plural($entityModelName))
            . '_for_'
            . strtolower(class_basename($this))
            . '_'
            . $this->id;

        /**
         * Otherwise, retrieve a list of current entities from the DB
         */

        return Cache::remember($cacheKey, Config::get('cache.ttl', 60), function () {
            return $this->getModelCurrentEntities($entityModelName, $namespace, true);
        });

    }

}
