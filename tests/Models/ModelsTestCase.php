<?php

namespace Vegvisir\TrustNoSql\Tests\Models;

use Vegvisir\TrustNoSql\Tests\TestCase;

class ModelsTestCase extends TestCase
{
    protected $factories = __DIR__.'/database/factories/models';

    public function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('trustnosql.teams.use_teams', true);
    }
}
