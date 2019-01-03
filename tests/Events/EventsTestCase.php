<?php

namespace Vegvisir\TrustNoSql\Tests\Events;

use Vegvisir\TrustNoSql\Tests\TestCase;

class EventsTestCase extends TestCase
{
    protected $permissionName = 'permission/test';

    protected $roleName = 'roletest';

    protected $teamName = 'teamtest';

    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/database/factories/events');
    }

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('trustnosql.teams.use_teams', true);

        $app['config']->set('trustnosql.events', [
            'use_events' => true,
            'observers' => [
                'Permission' => \Vegvisir\TrustNoSql\Tests\Infrastructure\Observers\PermissionObserver::class,
                'Role' => \Vegvisir\TrustNoSql\Tests\Infrastructure\Observers\RoleObserver::class,
                'Team' => \Vegvisir\TrustNoSql\Tests\Infrastructure\Observers\TeamObserver::class,
                'User' => \Vegvisir\TrustNoSql\Tests\Infrastructure\Observers\UserObserver::class,
            ]
        ]);
    }
}
