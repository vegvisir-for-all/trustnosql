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
use Vegvisir\TrustNoSql\Middleware\Permission as PermissionMiddleware;
use Vegvisir\TrustNoSql\Tests\Middleware\MiddlewareTestCase;

class PermissionMiddlewareTest extends MiddlewareTestCase
{
    public function testPermissionsConjunction_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new PermissionMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasPermission')
            ->with('permission/first')
            ->andReturn(true);
        
        $user->shouldReceive('hasPermission')
            ->with('permission/second')
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
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'permission/first&permission/second'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'permission/first&permission/second', 'api'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'permission/first&permission/second', 'web'));
    }

    public function testPermissionsConjunction_ShouldBeOk()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new PermissionMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasPermission')
            ->with('permission/first')
            ->andReturn(true);
        
        $user->shouldReceive('hasPermission')
            ->with('permission/second')
            ->andReturn(true);

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
        }, 'permission/first&permission/second'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'permission/first&permission/second', 'api'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'permission/first&permission/second', 'web'));
    }

    public function testPermissionsDisjunction_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new PermissionMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasPermission')
            ->with('permission/first')
            ->andReturn(false);
        
        $user->shouldReceive('hasPermission')
            ->with('permission/second')
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
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'permission/first|permission/second'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'permission/first|permission/second', 'api'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'permission/first|permission/second', 'web'));
    }

    public function testPermissionsDisjunction_ShouldBeOk()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new PermissionMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasPermission')
            ->with('permission/first')
            ->andReturn(true);
        
        $user->shouldReceive('hasPermission')
            ->with('permission/second')
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
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'permission/first|permission/second'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'permission/first|permission/second', 'api'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'permission/first|permission/second', 'web'));
    }
}
