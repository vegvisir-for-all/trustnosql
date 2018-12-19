<?php

namespace Vegvisir\TrustNoSql\Contracts;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
interface RoleInterface {

    /**
     * Moloquent belongs-to-many relationship with the permission model.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function permissions();

    public function users();

    /**
     * Syncs permission(s) to a Role.
     *
     * @param string|array $permissions Array of permissions or comma-separated list.
     * @return void
     */
    public function syncPermissions($permissions);

    /**
     * Attaches permission(s) to a Role
     *
     * @param string|array $permissions Array of permissions or comma-separated list.
     * @return void
     */
    public function attachPermissions($permissions);

    /**
     * Detaches permission(s) from a Role
     *
     * @param string|array $permissions Array of permissions or comma-separated list.
     * @return void
     */
    public function detachPermissions($permissions);

    /**
     * Flush the role's cache.
     *
     * @return void
     */
    public function flushCache();

}
