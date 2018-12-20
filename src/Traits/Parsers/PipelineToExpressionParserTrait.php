<?php

namespace Vegvisir\TrustNoSql\Traits\Parsers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
trait PipelineToExpressionParserTrait {


    /**
     * Translate pipeline to expression
     *
     * @param $entitnyName
     * @param $entitiesPipeline
     * @return string
     * @throws \Exception
     */
    public static function pipelineToExpression($entityName, $entitiesPipeline)
    {
        if(!is_string($entitiesPipeline) || !$entitiesPipeline) {
            return 'true';
        }

        $areAmpersands = false !== strpos('&', $entitiesPipeline) ? true : false;
        $areBars = false !== strpos('|', $entitiesPipeline) ? true : false;

        if($areAmpersands && $areBars) {
            throw new \Exception;
        }

        $pattern = '/([A-Za-z0-9\*\/]+)/im';
        $replace = $entityName . ':\1';
        $expression = preg_replace($pattern, $replace, $entitiesPipeline);

        return $expression;
    }

}
