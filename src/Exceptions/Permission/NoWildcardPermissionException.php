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
