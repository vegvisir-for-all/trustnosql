<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/database/factories');
    }

    protected function getEnvironmentSetUp($app)
    {
        // Setup mongodb connection
        $app['config']->set('database.default', 'mongodb');
        $app['config']->set('database.connections.mongodb', [
            'driver'   => 'mongodb',
            'host'     => env('DB_HOST', 'mongodb'),
            'port'     => env('DB_PORT', 27017),
            'database' => env('DB_DATABASE', 'trustnosql'),
            'username' => env('DB_USERNAME', 'trustnosql'),
            'password' => env('DB_PASSWORD', 'trustnosql'),
            'options'  => [
                'database' => 'admin' // sets the authentication database required by mongo 3
            ]
        ]);
        $app['config']->set('trustnosql.user_models.users', 'Vegvisir\TrustnoSql\Tests\Infrastructure\Models\User');
        $app['config']->set('cache', [
            'default' => 'file',
            'stores' => [
                'file' => [
                    'driver' => 'file',
                    'path' => storage_path('framework/cache/data')
                ]
            ]
        ]);
    }

    protected function getPackageProviders($app)
    {
        return [
            'Jenssegers\Mongodb\MongodbServiceProvider',
            'Vegvisir\TrustNoSql\TrustNoSqlServiceProvider'
        ];
    }
}
