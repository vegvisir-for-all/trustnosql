<?php

namespace Vegvisir\TrustNoSql\Tests\Events;

use Vegvisir\TrustNoSql\Tests\TestCase;

class EventsTestCase extends TestCase
{
    protected $factories = __DIR__.'database/factories/events';

    protected $permissionName = 'permission/test';

    protected $roleName = 'role-test';

    protected $teamName = 'team-test';

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
