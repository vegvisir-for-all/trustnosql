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

namespace Vegvisir\TrustNoSql\Middleware\Parser;

use Vegvisir\TrustNoSql\Traits\Parsers\ExpressionToBoolParserTrait;
use Vegvisir\TrustNoSql\Traits\Parsers\PipelineToExpressionParserTrait;

class LogicStringParser
{
    use ExpressionToBoolParserTrait, PipelineToExpressionParserTrait;
}
