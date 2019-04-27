<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Traits\Parsers;

use BracketChecker\BracketChecker;
use Closure;
use Vegvisir\TrustNoSql\Exceptions\Parser\BracketsMismatchException;

trait ExpressionToBoolParserTrait
{
    /**
     * Parse logic string into boolean value.
     *
     * @param string  $logicString
     * @param Closure $closure     Method to be called when translation of instruction to bools is performed
     *
     * @return bool
     */
    public static function expressionToBool($logicString, Closure $closure)
    {
        static::checkBrackets($logicString);

        $logicString = static::doubleOperands(static::addBracketsToInstructions(static::cleanWhitespace($logicString)));

        $i = 0;

        while (static::hasAnyBrackets($logicString)) {
            /**
             * Step 0
             * Reduce basic brackets with middleware instructions.
             */
            $logicString = static::instructionToBool($logicString, $closure);

            /**
             * Step 1
             * Reduce basic brackets with single bool.
             */
            $logicString = static::reduceSingleBools($logicString);

            /**
             * Step 2
             * Reduce bool&&bool and bool||bool.
             */
            $logicString = static::reduceSingleComparisons($logicString);

            /**
             * Step 3
             * Putting conjunction into brackets.
             */
            $oldLogicString = $logicString;

            $logicString = static::putConjunctionIntoBrackets($logicString);

            if ($oldLogicString !== $logicString) {
                continue;
            }

            /**
             * Step 4
             * Putting disjunction into brackets.
             */
            $logicString = static::putDisjunctionIntoBrackets($logicString);
        }

        return filter_var($logicString, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Put FIRST disjunction (like ' false || true ') into brackets, so the parser can
     * solve operation on next iteration.
     *
     * @param string $logic       string Logic string
     * @param mixed  $logicString
     *
     * @return string
     */
    protected static function putDisjunctionIntoBrackets($logicString)
    {
        return preg_replace('/(true|false)([\|]{2})(true|false)/im', '(\1\2\3)', $logicString, 1);
    }

    /**
     * Put all conjunctions (like ' false && true ') into brackets, so the parser can
     * solve operation on next iteration.
     *
     * @param string $logic       string Logic string
     * @param mixed  $logicString
     *
     * @return string
     */
    protected static function putConjunctionIntoBrackets($logicString)
    {
        return preg_replace('/(true|false)([\&]{2})(true|false)/im', '(\1\2\3)', $logicString);
    }

    /**
     * Reduces loose operations with two vars and replaces them with string reflection
     * of bool result.
     *
     * @param string $logic       string Logic string
     * @param mixed  $logicString
     *
     * @return string
     */
    protected static function reduceSingleComparisons($logicString)
    {
        $matches = [];

        while (1 <= preg_match_all('/\((true|false)([\&\|]{2})(true|false)\)/im', $logicString, $matches)) {
            foreach ($matches[0] as $key => $fullString) {
                $result = '||' === $matches[2][$key]
                    ? filter_var($matches[1][$key], FILTER_VALIDATE_BOOLEAN) || filter_var($matches[3][$key], FILTER_VALIDATE_BOOLEAN)
                    : filter_var($matches[1][$key], FILTER_VALIDATE_BOOLEAN) && filter_var($matches[3][$key], FILTER_VALIDATE_BOOLEAN);

                $strResult = $result ? 'true' : 'false';

                $logicString = str_replace($fullString, $strResult, $logicString);
            }
        }

        return $logicString;
    }

    /**
     * Reduces single bools in brackets.
     *
     * @param string $logic       string Logic string
     * @param mixed  $logicString
     *
     * @return string
     */
    protected static function reduceSingleBools($logicString)
    {
        while (false !== strpos($logicString, '(false)') || false !== strpos($logicString, '(true)')) {
            $logicString = str_replace('(false)', 'false', $logicString);
            $logicString = str_replace('(true)', 'true', $logicString);
        }

        return $logicString;
    }

    /**
     * Performs an actual operation on logic instruction and replaces instruction
     * with string reflection of bool result.
     *
     * @param string  $logic       string Logic string
     * @param Closure $closure     Method to be called when translating is performed
     * @param mixed   $logicString
     *
     * @return string
     */
    protected static function instructionToBool($logicString, Closure $closure)
    {
        $matches = [];
        while (1 <= preg_match('/\([A-Za-z0-9*]*\:[A-Za-z0-9*\/]*\)/im', $logicString, $matches)) {
            $exploded = explode(':', substr($matches[0], 1, \strlen($matches[0]) - 2));

            $result = $closure($exploded[0], $exploded[1]);

            $strResult = $result ? 'true' : 'false';

            // Has to be changed with actual method
            $logicString = str_replace($matches[0], $strResult, $logicString);
        }

        return $logicString;
    }

    /**
     * Checks whether logic string has any brackets.
     *
     * @param string $logic       string Logic string
     * @param mixed  $logicString
     *
     * @return bool
     */
    protected static function hasAnyBrackets($logicString)
    {
        return false !== strpos($logicString, '(') || false !== strpos($logicString, ')');
    }

    /**
     * Checks for correct depth of brackets in logic string.
     *
     * @param string $logicString logic string
     *
     * @throws BracketsMismatchException
     */
    protected static function checkBrackets($logicString)
    {
        $logicString = preg_replace('/[A-Za-z\:\&\|\/\*\-\_]/', '', $logicString);

        $checker = new BracketChecker();
        $checker->setString($logicString);

        try {
            $checker->check();
        } catch (\Exception $e) {
            throw new BracketsMismatchException();
        }
    }

    /**
     * Cleans logic string of any whitespace.
     *
     * @param string $logic       string Logic string
     * @param mixed  $logicString
     *
     * @return string
     */
    protected static function cleanWhitespace($logicString)
    {
        return preg_replace('/\s+/', '', $logicString);
    }

    /**
     * Adds brackets to single middleware instructions.
     *
     * @param string $logic       string Logic string
     * @param mixed  $logicString
     *
     * @return string
     */
    protected static function addBracketsToInstructions($logicString)
    {
        return preg_replace('/([A-Za-z*]{1,}:[A-Za-z*\/]{1,})/im', '(\1)', $logicString);
    }

    /**
     * Changes single TrustNoSql operands into PHP-like ones.
     *
     * @param string $logic       string Logic string
     * @param mixed  $logicString
     *
     * @return string
     */
    protected static function doubleOperands($logicString)
    {
        foreach ([
            '|' => '||',
            '&' => '&&',
        ] as $from => $to) {
            $logicString = str_replace($from, $to, $logicString);
        }

        return $logicString;
    }
}
