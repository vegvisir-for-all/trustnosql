<?php

namespace Vegvisir\TrustNoSql\Tests\Models;

use Vegvisir\TrustNoSql\Tests\TestCase;

class ModelsTestCase extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->withFactories(__DIR__.'/database/factories/models');
    }

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('trustnosql.teams.use_teams', true);
    }
}
