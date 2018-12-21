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

namespace Vegvisir\TrustNoSql\Traits\Cacheable;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

trait ModelCacheableTrait
{
    /**
     * Flush cache for given model (optionally, an array of entities to be flushed,
     * can be passed).
     *
     * @param null|array $entityModelNames
     */
    public function currentModelFlushCache($entityModelNames = null)
    {
        if (null === $entityModelNames) {
            $entityModelNames = [
                'permission',
                'role',
                'user',
                'team',
            ];
        }

        if (!\is_array($entityModelNames)) {
            $entityModelNames = explode(',', $entityModelNames);
        }

        foreach ($entityModelNames as $entityModelName) {
            try {
                $cacheKey = 'trustnosql_'
                    .strtolower(str_plural($entityModelName))
                    .'_for_'
                    .strtolower(class_basename($this))
                    .'_'
                    .$this->id;

                Cache::forget($cacheKey);
            } catch (\Exception $e) {
            }
        }
    }

    /**
     * Gets a list of cached model entities' names or stores it to cache, if it wasn't
     * present before.
     *
     * @param string      $entityModelName
     * @param null|string $namespace
     *
     * @return array
     */
    protected function getModelCachedEntities($entityModelName, $namespace = null)
    {
        $cacheKey = 'trustnosql_'
            .strtolower(str_plural($entityModelName))
            .'_for_'
            .strtolower(class_basename($this))
            .'_'
            .$this->id;

        if (null !== $namespace) {
            $cacheKey .= '_'.$namespace;
        }

        return Cache::remember($cacheKey, Config::get('cache.ttl', 60), function () use ($entityModelName, $namespace) {
            return $this->getModelCurrentEntities($entityModelName, $namespace, true);
        });
    }
}
