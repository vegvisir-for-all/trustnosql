<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Middleware\Parser;

use Vegvisir\TrustNoSql\Traits\Parsers\ExpressionToBoolParserTrait;
use Vegvisir\TrustNoSql\Traits\Parsers\PipelineToExpressionParserTrait;

class LogicStringParser
{
    use ExpressionToBoolParserTrait, PipelineToExpressionParserTrait;
}
