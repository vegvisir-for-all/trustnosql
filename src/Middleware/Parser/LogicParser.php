<?php

namespace Vegvisir\TrustNoSql\Middleware\Parser;

use \BracketChecker\BracketChecker;
use Vegvisir\TrustNoSql\Exceptions\Parser\BracketsMismatchException;

class LogicParser
{

    public static function parseLogicString($logicString = '')
    {
        static::checkBrackets($logicString);

        $logicString = static::doubleOperands(static::addBracketsToInstructions(static::cleanWhitespace($logicString)));

        while(static::hasAnyBrackets($logicString)) {

            /**
             * Step 0
             * Reduce basic brackets with middleware instructions
             */
             $logicString = static::instructionToBool($logicString);

             /**
             * Step 1
             * Reduce basic brackets with single bool
             */
            $logicString = static::reduceSingleBools($logicString);

            /**
             * Step 2
             * Reduce bool&&bool and bool||bool
             */
            $logicString = static::reduceSingleComparisons($logicString);

            /**
             * Step 3
             * Putting conjunction into brackets
             */

            $oldLogicString = $logicString;

            $logicString = static::putConjunctionIntoBrackets($logicString);

            if($oldLogicString !== $logicString) {
                continue;
            }

            /**
             * Step 4
             * Putting disjunction into brackets
             */
            $logicString = static::putDisjunctionIntoBrackets($logicString);

        }

        return filter_var($logicString, FILTER_VALIDATE_BOOLEAN);
    }

    protected static function putDisjunctionIntoBrackets($logicString)
    {
        return preg_replace('/(true|false)([\|]{2})(true|false)/im', '(\1\2\3)', $logicString, 1);
    }

    protected static function putConjunctionIntoBrackets($logicString)
    {
        return preg_replace('/(true|false)([\&]{2})(true|false)/im', '(\1\2\3)', $logicString);
    }

    protected static function reduceSingleComparisons($logicString)
    {
        $matches = [];

        while(1 == preg_match_all('/\((true|false)([\&\|]{2})(true|false)\)/im', $logicString, $matches)) {

            foreach($matches[0] as $key => $fullString) {
                $result = $matches[2][$key] == '||'
                    ? filter_var($matches[1][$key], FILTER_VALIDATE_BOOLEAN) || filter_var($matches[3][$key], FILTER_VALIDATE_BOOLEAN)
                    : filter_var($matches[1][$key], FILTER_VALIDATE_BOOLEAN) && filter_var($matches[3][$key], FILTER_VALIDATE_BOOLEAN);

                    $strResult = $result ? 'true' : 'false';

                    $logicString = str_replace($fullString, $strResult, $logicString);
            }
        }

        return $logicString;
    }

    protected static function reduceSingleBools($logicString)
    {
        while(false !== strpos($logicString, '(false)') || false !== strpos($logicString, '(true)')) {
            $logicString = str_replace('(false)', 'false', $logicString);
            $logicString = str_replace('(true)', 'true', $logicString);
        }

        return $logicString;
    }

    protected static function instructionToBool($logicString)
    {

        $matches = [];
        while(1 == preg_match('/\([A-Za-z0-9*]*\:[A-Za-z0-9*\/]*\)/im', $logicString, $matches)) {

            $strResult = rand(0,1) == 1 ? 'true' : 'false';

            // Has to be changed with actual method
            $logicString = str_replace($matches[0], $strResult, $logicString);
        }

        return $logicString;

    }

    protected static function hasAnyBrackets($logicString)
    {
        return (false !== strpos($logicString, '(') || false !== strpos($logicString, ')'));
    }

    protected static function checkBrackets($logicString)
    {

        $logicString = preg_replace('/[A-Za-z\:\&\|\/\*]/', '', $logicString);

        $checker = new BracketChecker;
        $checker->setString($logicString);

        if(!$checker->check()) {
            throw new BracketsMismatchException;
        }
    }

    protected static function cleanWhitespace($logicString)
    {
        return preg_replace('/\s+/', '', $logicString);
    }

    protected static function addBracketsToInstructions($logicString)
    {
        return preg_replace('/([A-Za-z*]{1,}:[A-Za-z*\/]{1,})/im', '(\1)', $logicString);
    }

    protected static function doubleOperands($logicString)
    {
        foreach([
            '|' => '||',
            '&' => '&&'
        ] as $from => $to) {
            $logicString = str_replace($from, $to, $logicString);
        }

        return $logicString;
    }



}
