<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Exceptions\Parser;

use Symfony\Component\HttpKernel\Exception\HttpException;

class BracketsMismatchException extends HttpException
{
    /**
     * Exception constructor.
     *
     * @param null|\Exception $previous Previous exception
     * @param int             $code
     *
     * @throws new \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function __construct(\Exception $previous = null, $code = 0)
    {
        parent::__construct(500, 'Brackets mismatch', $previous, [], $code);
    }
}
