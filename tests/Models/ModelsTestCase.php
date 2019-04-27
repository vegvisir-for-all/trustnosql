<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests\Models;

use Vegvisir\TrustNoSql\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class ModelsTestCase extends TestCase
{
    protected $factories = __DIR__.'/../database/factories/models';

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('trustnosql.permissions.wildcards', [
            '*',
            'all',
        ]);
        $app['config']->set('trustnosql.teams.use_teams', true);
    }
}
