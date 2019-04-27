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
use Vegvisir\TrustNoSql\Middleware\Permission as PermissionMiddleware;
use Vegvisir\TrustNoSql\Middleware\Role as RoleMiddleware;
use Vegvisir\TrustNoSql\Middleware\Team as TeamMiddleware;

/**
 * @internal
 * @coversNothing
 */
final class GuestMiddlewareTest extends MiddlewareTestCase
{
    public function testGuestPermissionShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $middleware = new PermissionMiddleware();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('guest')->andReturn(true);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'permission/test'));
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'permission/test', 'api'));
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'permission/test', 'web'));
    }

    public function testGuestPermissionShouldRedirectWithoutError()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);
        Config::set('trustnosql.middleware.handling', 'redirect');

        $middleware = new PermissionMiddleware();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('guest')->andReturn(true);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'permission/first&manager'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'permission/first&manager'));

        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'permission/first&manager', 'api'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'permission/first&manager', 'api'));

        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'permission/first&manager', 'web'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'permission/first&manager', 'web'));
    }

    public function testGuestPermissionShouldRedirectWithError()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);
        Config::set('trustnosql.middleware.handling', 'redirect');
        Config::set('trustnosql.middleware.handlers.redirect.message.content', 'The message was flashed');

        $middleware = new PermissionMiddleware();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('guest')->andReturn(true);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'permission/first&manager'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'permission/first&manager'));

        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'permission/first&manager', 'api'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'permission/first&manager', 'api'));

        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'permission/first&manager', 'web'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'permission/first&manager', 'web'));

        $this->assertArrayHasKey('error', session()->all());
        $this->assertSame('The message was flashed', session()->get('error'));
    }

    public function testGuestRoleShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new RoleMiddleware();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('guest')->andReturn(true);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'admin'));
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'admin', 'api'));
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'admin', 'web'));
    }

    public function testGuestRoleShouldRedirectWithoutError()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);
        Config::set('trustnosql.middleware.handling', 'redirect');

        $middleware = new RoleMiddleware();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('guest')->andReturn(true);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'admin&manager'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'admin&manager'));

        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'admin&manager', 'api'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'admin&manager', 'api'));

        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'admin&manager', 'web'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'admin&manager', 'web'));
    }

    public function testGuestRoleShouldRedirectWithError()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);
        Config::set('trustnosql.middleware.handling', 'redirect');
        Config::set('trustnosql.middleware.handlers.redirect.message.content', 'The message was flashed');

        $middleware = new RoleMiddleware();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('guest')->andReturn(true);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'admin&manager'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'admin&manager'));

        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'admin&manager', 'api'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'admin&manager', 'api'));

        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'admin&manager', 'web'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'admin&manager', 'web'));

        $this->assertArrayHasKey('error', session()->all());
        $this->assertSame('The message was flashed', session()->get('error'));
    }

    public function testGuestTeamTeamOffShouldBeOk()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new TeamMiddleware();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('guest')->andReturn(true);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'team'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'team', 'api'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'team', 'web'));
    }

    public function testGuestTeamTeamOnShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', true);

        $middleware = new TeamMiddleware();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('guest')->andReturn(true);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'team'));
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'team', 'api'));
        $this->assertSame(403, $middleware->handle($this->request, function () {
        }, 'team', 'web'));
    }

    public function testGuestTeamTeamOnShouldRedirectWithoutError()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', true);
        Config::set('trustnosql.middleware.handling', 'redirect');

        $middleware = new TeamMiddleware();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('guest')->andReturn(true);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'team'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'team'));

        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'team', 'api'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'team', 'api'));

        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'team', 'web'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'team', 'web'));
    }

    public function testGuestTeamTeamOnShouldRedirectWithError()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', true);
        Config::set('trustnosql.middleware.handling', 'redirect');
        Config::set('trustnosql.middleware.handlers.redirect.message.content', 'The message was flashed');

        $middleware = new TeamMiddleware();

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('guest')->andReturn(true);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'team'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'team'));

        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'team', 'api'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'team', 'api'));

        $this->assertObjectHasAttribute('content', $middleware->handle($this->request, function () {
        }, 'team', 'web'));
        $this->assertAttributeContains('/home', 'content', $middleware->handle($this->request, function () {
        }, 'team', 'web'));

        $this->assertArrayHasKey('error', session()->all());
        $this->assertSame('The message was flashed', session()->get('error'));
    }
}
