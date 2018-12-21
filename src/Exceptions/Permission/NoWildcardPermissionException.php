<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Exceptions\Permission;

use Symfony\Component\HttpKernel\Exception\HttpException;

class NoWildcardPermissionException extends HttpException
{
    /**
     * Exception constructor.
     *
     * @param string          $permissionName A faulty permission name
     * @param null|\Exception $previous       Previous exception
     * @param int             $code
     *
     * @throws new \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function __construct($permissionName, \Exception $previous = null, $code = 0)
    {
        parent::__construct(500, "A permission \"${permissionName}\" is not a wildcard permission", $previous, [], $code);
    }
}
