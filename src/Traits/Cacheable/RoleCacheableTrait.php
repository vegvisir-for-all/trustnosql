<?php

namespace Vegvisir\TrustNoSql\Traits\Cacheable;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */

trait RoleCacheableTrait {

    /**
     * Retrieves (from cache) an array of Role's permission names, excluding wildcard permissions
     *
     * @param string|null $namespace Namespace of permissions to be retrieved
     * @return array
     */
    public function getRoleCachedPermissions($namespace) {}

}
