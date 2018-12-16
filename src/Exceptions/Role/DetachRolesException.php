<?php

namespace Vegvisir\TrustNoSql\Exceptions\Role;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Symfony\Component\HttpKernel\Exception\HttpException;

class DetachRolesException extends HttpException
{

    /**
     * Exception constructor.
     *
     * @param \Exception|null $previous Previous exception
     * @param int $code
     * @throws new \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function __construct(\Exception $previous = null, $code = 0)
    {
        parent::__construct(500, "Error detaching roles", $previous, [], $code);
    }
}
