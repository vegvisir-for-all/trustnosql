<?php

namespace Vegvisir\TrustNoSql\Tests\Middleware;

use Mockery as m;
use Vegvisir\TrustNoSql\Tests\TestCase;

class MiddlewareTestCase extends TestCase
{

    protected $request;

    protected $guard;

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('trustnosql.teams.use_teams', true);

        $app['config']->set('auth', [
            'defaults' => [
                'guard' => 'web'
            ],
            'guards' => [
                'web' => [
                    'driver' => 'session',
                    'provider' => 'users'
                ]
            ],
            'providers' => [
                'users' => [
                    'driver' => 'eloquent',
                    'model' => \Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User::class
                ]
            ]
        ]);

        $app['config']->set('trustnosql.middleware', [
            'register' => true,
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
            ]
        ]);

        $this->request = m::mock('Illuminate\Http\Request');
        $this->guard = m::mock('Illuminate\Contracts\Auth\Guard');
    }
}
