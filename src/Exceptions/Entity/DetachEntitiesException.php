<?php

namespace Vegvisir\TrustNoSql\Exceptions\Entity;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Symfony\Component\HttpKernel\Exception\HttpException;

class DetachEntitiesException extends HttpException
{

    /**
     * Exception constructor.
     *
     * @param string $entitiesModelName Entities model name (what is being attached)
     * @param \Exception|null $previous Previous exception
     * @param int $code
     * @throws new \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function __construct($entitiesModelName, \Exception $previous = null, $code = 0)
    {
        $entitiesModelName = strtolower(str_plural($entitiesModelName));
        parent::__construct(500, "Error attaching $entitiesModelName", $previous, [], $code);
    }
}
