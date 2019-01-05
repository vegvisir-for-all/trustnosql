<?php

namespace Vegvisir\TrustNoSql\Tests\Grabbable;

use Vegvisir\TrustNoSql\Tests\TestCase;

class GrabbableTestCase extends TestCase
{
    protected $factories = __DIR__.'database/factories/grabbable';

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('trustnosql.teams.use_teams', true);
    }
}
