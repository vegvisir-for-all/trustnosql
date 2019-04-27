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

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Mockery as m;
use Vegvisir\TrustNoSql\Middleware\Team as TeamMiddleware;

/**
 * @internal
 * @coversNothing
 */
final class TeamMiddlewareTest extends MiddlewareTestCase
{
    public function testTeamsConjunctionShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', true);

        $middleware = new TeamMiddleware();
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasTeam')
            ->with('first')
            ->andReturn(true)
        ;

        $user->shouldReceive('hasTeam')
            ->with('second')
            ->andReturn(false)
        ;

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $this->guard->shouldReceive('guest')->andReturn(false);
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('user')->andReturn($user);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'first&second'));
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'first&second', 'api'));
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'first&second', 'web'));
    }

    public function testTeamsConjunctionShouldBeOk()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', true);

        $middleware = new TeamMiddleware();
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasTeam')
            ->with('first')
            ->andReturn(true)
        ;

        $user->shouldReceive('hasTeam')
            ->with('second')
            ->andReturn(true)
        ;

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $this->guard->shouldReceive('guest')->andReturn(false);
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('user')->andReturn($user);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'first&second'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'first&second', 'api'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'first&second', 'web'));
    }

    public function testTeamsDisjunctionShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', true);

        $middleware = new TeamMiddleware();
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasTeam')
            ->with('first')
            ->andReturn(false)
        ;

        $user->shouldReceive('hasTeam')
            ->with('second')
            ->andReturn(false)
        ;

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $this->guard->shouldReceive('guest')->andReturn(false);
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('user')->andReturn($user);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'first|second'));
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'first|second', 'api'));
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'first|second', 'web'));
    }

    public function testTeamsDisjunctionShouldBeOk()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', true);

        $middleware = new TeamMiddleware();
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasTeam')
            ->with('first')
            ->andReturn(true)
        ;

        $user->shouldReceive('hasTeam')
            ->with('second')
            ->andReturn(false)
        ;

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $this->guard->shouldReceive('guest')->andReturn(false);
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('user')->andReturn($user);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'first|second'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'first|second', 'api'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'first|second', 'web'));
    }
}
