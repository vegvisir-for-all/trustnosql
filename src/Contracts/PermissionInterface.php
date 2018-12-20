<?php

namespace Vegvisir\TrustNoSql\Contracts;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
interface PermissionInterface
{

    /**
     * Moloquent belongs-to-many relationship with the role model.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function roles();

    /**
     * Moloquent belongs-to-many relationship with the team model.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function teams();

    /**
     * Moloquent belongs-to-many relationship with the user model.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function users();

}
