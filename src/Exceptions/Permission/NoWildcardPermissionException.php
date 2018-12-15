<?php

namespace Vegvisir\TrustNoSql\Exceptions\Permission;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Symfony\Component\HttpKernel\Exception\HttpException;

class NoWildcardPermissionException extends HttpException
{

    /**
     * Exception constructor.
     *
     * @param string $permissionName A faulty permission name
     * @param \Exception|null $previous Previous exception
     * @param int $code
     * @throws new \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function __construct($permissionName, \Exception $previous = null, $code = 0)
    {
        parent::__construct(500, "A permission \"$permissionName\" is not a wildcard permission", $previous, [], $code);
    }
}