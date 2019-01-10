<?php

namespace Vegvisir\TrustNoSql\Tests\Middleware;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Mockery as m;
use Vegvisir\TrustNoSql\Middleware\Permission as PermissionMiddleware;
use Vegvisir\TrustNoSql\Middleware\Role as RoleMiddleware;
use Vegvisir\TrustNoSql\Middleware\Team as TeamMiddleware;
use Vegvisir\TrustNoSql\Models\Permission as Permission;
use Vegvisir\TrustNoSql\Models\Role as Role;
use Vegvisir\TrustNoSql\Models\Team as Team;

class GuestMiddlewareTest extends MiddlewareTestCase
{
    public function testGuestPermission_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $middleware = new PermissionMiddleware;

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
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'permission/test'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'permission/test', 'api'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'permission/test', 'web'));
    }

    public function testGuestPermission_ShouldRedirectWithoutError()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);
        Config::set('trustnosql.middleware.handling', 'redirect');

        $middleware = new PermissionMiddleware;

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

    public function testGuestPermission_ShouldRedirectWithError()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);
        Config::set('trustnosql.middleware.handling', 'redirect');
        Config::set('trustnosql.middleware.handlers.redirect.message.content', 'The message was flashed');

        $middleware = new PermissionMiddleware;

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
        $this->assertEquals('The message was flashed', session()->get('error'));
    }

    public function testGuestRole_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new RoleMiddleware;

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
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin', 'api'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin', 'web'));
    }

    public function testGuestRole_ShouldRedirectWithoutError()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);
        Config::set('trustnosql.middleware.handling', 'redirect');

        $middleware = new RoleMiddleware;

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

    public function testGuestRole_ShouldRedirectWithError()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);
        Config::set('trustnosql.middleware.handling', 'redirect');
        Config::set('trustnosql.middleware.handlers.redirect.message.content', 'The message was flashed');

        $middleware = new RoleMiddleware;

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
        $this->assertEquals('The message was flashed', session()->get('error'));
    }

    public function testGuestTeam_TeamOff_ShouldBeOk()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);
        
        $middleware = new TeamMiddleware;

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

    public function testGuestTeam_TeamOn_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', true);
        
        $middleware = new TeamMiddleware;

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
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'team'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'team', 'api'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'team', 'web'));
    }

    public function testGuestTeam_TeamOn_ShouldRedirectWithoutError()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', true);
        Config::set('trustnosql.middleware.handling', 'redirect');

        $middleware = new TeamMiddleware;

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

    public function testGuestTeam_TeamOn_ShouldRedirectWithError()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', true);
        Config::set('trustnosql.middleware.handling', 'redirect');
        Config::set('trustnosql.middleware.handlers.redirect.message.content', 'The message was flashed');

        $middleware = new TeamMiddleware;

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
        $this->assertEquals('The message was flashed', session()->get('error'));
    }
}
