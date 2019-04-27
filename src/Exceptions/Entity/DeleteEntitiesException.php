<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Exceptions\Entity;

use Symfony\Component\HttpKernel\Exception\HttpException;

class DeleteEntitiesException extends HttpException
{
    /**
     * Exception constructor.
     *
     * @param string          $entitiesModelName Entities model name (what is being attached)
     * @param null|\Exception $previous          Previous exception
     * @param int             $code
     * @param mixed           $entityName
     *
     * @throws new \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function __construct($entitiesModelName, $entityName, \Exception $previous = null, $code = 0)
    {
        $entitiesModelName = strtolower(str_plural($entitiesModelName));
        parent::__construct(500, "Error attaching ${entitiesModelName} ${entityName}", $previous, [], $code);
    }
}
