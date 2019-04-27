<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests\Middleware;

use Mockery as m;
use Vegvisir\TrustNoSql\Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class MiddlewareTestCase extends TestCase
{
    protected $request;

    protected $guard;

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('trustnosql.teams.use_teams', true);

        $app['config']->set('auth', [
            'defaults' => [
                'guard' => 'web',
            ],
            'guards' => [
                'web' => [
                    'driver' => 'session',
                    'provider' => 'users',
                ],
            ],
            'providers' => [
                'users' => [
                    'driver' => 'eloquent',
                    'model' => \Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User::class,
                ],
            ],
        ]);

        $app['config']->set('trustnosql.middleware', [
            'handling' => 'abort',
            'handlers' => [
                'abort' => [
                    'code' => 403,
                ],
                'redirect' => [
                    'url' => '/home',
                    'message' => [
                        'type' => 'error',
                        'content' => '',
                    ],
                ],
            ],
        ]);

        $this->request = m::mock('Illuminate\Http\Request');
        $this->guard = m::mock('Illuminate\Contracts\Auth\Guard');
    }
}
