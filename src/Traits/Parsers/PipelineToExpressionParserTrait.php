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

use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Models\Role;

trait PipelineToExpressionParserTrait
{
    /**
     * Translate pipeline to expression.
     *
     * @param $entityName
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

        $areAmpersands = false !== strpos($entitiesPipeline, '&') ? true : false;
        $areBars = false !== strpos($entitiesPipeline, '|') ? true : false;

        if ($areAmpersands && $areBars) {
            throw new \Exception();
        }

        $pattern = '/([A-Za-z0-9\*\/]+)/im';
        $replace = $entityName.':\1';

        $entitiesPipeline = preg_replace($pattern, $replace, $entitiesPipeline);

        if($entityName !== 'role' || ($entityName == 'role' && false == Config::get('trustnosql.teams.use_teams'))) {
            return $entitiesPipeline;
        }

        $roleNames = explode($areAmpersands ? '&' : '|', $entitiesPipeline);

        foreach($roleNames as $roleName) {

            $roleName = str_replace('role:', '', $roleName);

            $role = Role::where('name', $roleName)->first();

            $teams = collect($role->teams)->map(function ($item, $key) {
                return 'team:' . $item->name;
            })->toArray();

            if(count($teams) > 1) {
                $teamsExpression = implode('|', $teams);
            } elseif(count($teams) == 1) {
                $teamsExpression = $teams[0];
            } else {
                $teamsExpression = '';
            }

            $entitiesPipeline = str_replace('role:' . $roleName, '(role:' . $roleName . "&($teamsExpression))", $entitiesPipeline);
        }

        return $entitiesPipeline;

    }
}
