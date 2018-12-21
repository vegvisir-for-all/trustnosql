<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Contracts;

interface PermissionInterface
{
    /**
     * Moloquent belongs-to-many relationship with the role model.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function roles();

    /**
     * Moloquent belongs-to-many relationship with the user model.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function users();
}
