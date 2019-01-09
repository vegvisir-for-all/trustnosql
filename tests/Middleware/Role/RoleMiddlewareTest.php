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
use Vegvisir\TrustNoSql\Middleware\Role as RoleMiddleware;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;
use Vegvisir\TrustNoSql\Tests\Middleware\MiddlewareTestCase;

class RoleMiddlewareTest extends MiddlewareTestCase
{

    public function testRolesConjunction_TeamOff_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new RoleMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasRole')
            ->with('admin')
            ->andReturn(true);
        
        $user->shouldReceive('hasRole')
            ->with('superadmin')
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
        }, 'admin&superadmin'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin&superadmin', 'api'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin&superadmin', 'web'));
    }

    public function testRolesConjunction_TeamOff_ShouldBeOk()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new RoleMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasRole')
            ->with('admin')
            ->andReturn(true);
        
        $user->shouldReceive('hasRole')
            ->with('superadmin')
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
        }, 'admin&superadmin'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'admin&superadmin', 'api'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'admin&superadmin', 'web'));
    }

    public function testRolesDisjunction_Team_Off_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new RoleMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasRole')
            ->with('admin')
            ->andReturn(false);
        
        $user->shouldReceive('hasRole')
            ->with('superadmin')
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
        }, 'admin|superadmin'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin|superadmin', 'api'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin|superadmin', 'web'));
    }

    public function testRolesDisjunction_teamOff_ShouldBeOk()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new RoleMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasRole')
            ->with('admin')
            ->andReturn(true);
        
        $user->shouldReceive('hasRole')
            ->with('superadmin')
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
        }, 'admin|superadmin'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'admin|superadmin', 'api'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'admin|superadmin', 'web'));
    }

    public function testRoles_TeamOn_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', true);

        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $team = Team::create(['name' => 'team']);
        $user = User::where(1)->first();

        $middleware = new RoleMiddleware;

        $user->attachRoles('admin,manager');
        $admin->attachTeams('team');
        $manager->attachTeams('team');

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
        }, 'admin|manager'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin|manager', 'api'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin|manager', 'web'));
    }

    public function testRoles_TeamOn_ShouldBeOk()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        Config::set('trustnosql.teams.use_teams', true);
        $user = User::where(1)->first();
        $admin = Role::where('name', 'admin')->first();
        $manager = Role::where('name', 'manager')->first();

        $middleware = new RoleMiddleware;

        $user->attachRoles('admin,manager');
        $user->attachTeams('team');
        $admin->attachTeams('team');
        $manager->attachTeams('team');


        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $this->guard->shouldReceive('guest')->andReturn(false);
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('user')->andReturn($user);


        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'admin&manager'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'admin&manager', 'api'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'admin&manager', 'web'));
    }
}
