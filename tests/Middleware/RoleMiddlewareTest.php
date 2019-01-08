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

class RoleMiddlewareTest extends MiddlewareTestCase
{
    public function testHandleIsGuestShouldAbort403()
    {

        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new RoleMiddleware;

        Auth::shouldReceive('guard')->with('web')->andReturn($this->guard);
        $this->guard->shouldReceive('guest')->andReturn(true);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin', 'web'));
    }

    public function testHandleIsLoggedInWithMismatchingRoleShouldAbort403()
    {

        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new RoleMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasRole')
            ->with(
                m::anyOf('admin', 'user')
            )
            ->andReturn(false);

        $this->guard->shouldReceive('guest')->andReturn(false);
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('user')->andReturn($user);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin|user'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin|user', 'api'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin|user', 'web'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin&user'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin&user', 'api'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin&user', 'web'));
    }

    public function testHandleIsLoggedInWithOneRoleTeamOffShouldAbort403()
    {

        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new RoleMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasRole')
            ->with('admin')
            ->andReturn(false);

        $user->shouldReceive('hasRole')
            ->with('manager')
            ->andReturn(true);

        $this->guard->shouldReceive('guest')->andReturn(false);
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('user')->andReturn($user);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin&manager'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin&manager', 'api'));
        $this->assertEquals(403, $middleware->handle($this->request, function () {
        }, 'admin&manager', 'web'));

    }

    public function testHandleIsLoggedInWithOneRoleTeamOffShouldNotAbort()
    {

        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new RoleMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasRole')
            ->with('admin')
            ->andReturn(false);

        $user->shouldReceive('hasRole')
            ->with('manager')
            ->andReturn(true);

        $this->guard->shouldReceive('guest')->andReturn(false);
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('user')->andReturn($user);

        $this->assertNull($middleware->handle($this->request, function () {
        }, 'admin|manager'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'admin|manager', 'api'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'admin|manager', 'web'));
    }

    public function testHandleIsLoggedInWithTwoRolesTeamOffShouldNotAbort()
    {

        Config::set('trustnosql.teams.use_teams', false);

        $middleware = new RoleMiddleware;
        $user = m::mock('Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User')->makePartial();

        $user->shouldReceive('hasRole')
            ->with('admin')
            ->andReturn(true);

        $user->shouldReceive('hasRole')
            ->with('manager')
            ->andReturn(true);

        $this->guard->shouldReceive('guest')->andReturn(false);
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('user')->andReturn($user);

        $this->assertNull($middleware->handle($this->request, function () {
        }, 'admin&manager'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'admin&manager', 'api'));
        $this->assertNull($middleware->handle($this->request, function () {
        }, 'admin&manager', 'web'));
    }

    public function testHandleIsLoggedInWithTwoRolesTeamOnShouldAbort403()
    {
        Config::set('trustnosql.teams.use_teams', true);

        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $team = Team::create(['name' => 'team']);
        $user = User::where(1)->first();

        $middleware = new RoleMiddleware;

        $user->attachRoles('admin,manager');
        $admin->attachTeams('team');
        $manager->attachTeams('team');

        $this->guard->shouldReceive('guest')->andReturn(false);
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('user')->andReturn($user);
        App::shouldReceive('abort')->with(403)->andReturn(403);

        $this->assertEquals(403, $middleware->handle($this->request, function () {}, 'admin|manager'));
    }

    public function testHandleIsLoggedInWithTwoRolesTeamOnShouldNotAbort()
    {
        Config::set('trustnosql.teams.use_teams', true);
        $user = User::where(1)->first();
        $admin = Role::where('name', 'admin')->first();
        $manager = Role::where('name', 'manager')->first();

        $middleware = new RoleMiddleware;

        $user->attachRoles('admin,manager');
        $user->attachTeams('team');
        $admin->attachTeams('team');
        $manager->attachTeams('team');

        $this->guard->shouldReceive('guest')->andReturn(false);
        Auth::shouldReceive('guard')->with(m::anyOf('api', 'web'))->andReturn($this->guard);
        $this->guard->shouldReceive('user')->andReturn($user);

        $this->assertNull($middleware->handle($this->request, function () {}, 'admin&manager'));
    }
}
