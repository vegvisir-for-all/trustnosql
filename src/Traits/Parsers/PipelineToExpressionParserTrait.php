<?php

namespace Vegvisir\TrustNoSql\Traits\Parsers;

trait PipelineToExpressionParserTrait {


        /**
         * Entities can be given in one of two following methods:
         *
         * 1. 'vegvisir,backpet' with additional parameter $requireAll
         * 2. 'vegvisir&backpet' as AND
         * 3. 'vegvisir|backpet' as OR pipeline
         *
         * Function should throw error when both & and | pipeline operators are present
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
