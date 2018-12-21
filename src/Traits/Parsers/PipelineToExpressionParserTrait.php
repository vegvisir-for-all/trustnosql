<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Traits\Parsers;

trait PipelineToExpressionParserTrait
{
    /**
     * Translate pipeline to expression.
     *
     * @param $entitnyName
     * @param $entitiesPipeline
     * @param mixed $entityName
     *
     * @throws \Exception
     *
     * @return string
     */
    public static function pipelineToExpression($entityName, $entitiesPipeline)
    {
        if (!\is_string($entitiesPipeline) || !$entitiesPipeline) {
            return 'true';
        }

        $areAmpersands = false !== strpos('&', $entitiesPipeline) ? true : false;
        $areBars = false !== strpos('|', $entitiesPipeline) ? true : false;

        if ($areAmpersands && $areBars) {
            throw new \Exception();
        }

        $pattern = '/([A-Za-z0-9\*\/]+)/im';
        $replace = $entityName.':\1';

        return preg_replace($pattern, $replace, $entitiesPipeline);
    }
}
