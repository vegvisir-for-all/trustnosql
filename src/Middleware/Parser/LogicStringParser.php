<?php

namespace Vegvisir\TrustNoSql\Middleware\Parser;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Traits\Parsers\ExpressionToBoolParserTrait;
use Vegvisir\TrustNoSql\Traits\Parsers\PipelineToExpressionParserTrait;

class LogicStringParser
{
    use ExpressionToBoolParserTrait, PipelineToExpressionParserTrait;
}
