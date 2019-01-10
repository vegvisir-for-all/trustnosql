<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests\Middleware;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Mockery as m;
use Vegvisir\TrustNoSql\Middleware\Reject as RejectMiddleware;
use Vegvisir\TrustNoSql\Tests\Middleware\MiddlewareTestCase;

class RejectMiddlewareTest extends MiddlewareTestCase
{
    public function testRejectMiddleware_TeamOff_ShouldAbort403()
    { 
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new RejectMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasPermission')
            ->with(m::anyOf('permission/first', 'permission/second'))
            ->andReturn(true);
        $user->shouldReceive('hasPermission')
            ->with(m::anyOf('permission/third', 'permission/fourth'))
            ->andReturn(false);

        $user->shouldReceive('hasRole')
            ->with(m::anyOf('first', 'second'))
            ->andReturn(true);
        $user->shouldReceive('hasRole')
            ->with(m::anyOf('third', 'fourth'))
            ->andReturn(false);

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
        $expressions = [
            'role:first|role:third',
            '(role:first&role:third)|(role:fourth|role:second)',
            '(role:first&permission:permission/first)|permission:permission/third',
            '(permission:permission/first|permission:permission/third)&role:first',
            '(role:third&permission:permission/third)|(role:first&permission:permission/first)'
        ];

        foreach($expressions as $expression) {
            $this->assertEquals(403, $middleware->handle($this->request, function () {}, $expression));
            $this->assertEquals(403, $middleware->handle($this->request, function () {}, $expression, 'api'));
            $this->assertEquals(403, $middleware->handle($this->request, function () {}, $expression, 'web'));
        }
    }

    public function testTrustMiddleware_TeamOff_ShouldBeOk()
    { 
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new RejectMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasPermission')
            ->with(m::anyOf('permission/first', 'permission/second'))
            ->andReturn(true);
        $user->shouldReceive('hasPermission')
            ->with(m::anyOf('permission/third', 'permission/fourth'))
            ->andReturn(false);

        $user->shouldReceive('hasRole')
            ->with(m::anyOf('first', 'second'))
            ->andReturn(true);
        $user->shouldReceive('hasRole')
            ->with(m::anyOf('third', 'fourth'))
            ->andReturn(false);

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
        $expressions = [
            'role:first&role:third',
            '(role:first&role:third)|role:fourth',
            '(role:third|permission:permission/first)&permission:permission/third',
            '(permission:permission/first|permission:permission/third)&role:fourth',
            '(role:third&permission:permission/third)|(role:fourth&permission:permission/first)'
        ];

        foreach($expressions as $expression) {
            $this->assertEquals(403, $middleware->handle($this->request, function () {}, $expression));
            $this->assertEquals(403, $middleware->handle($this->request, function () {}, $expression, 'api'));
            $this->assertEquals(403, $middleware->handle($this->request, function () {}, $expression, 'web'));
        }
    }
}
