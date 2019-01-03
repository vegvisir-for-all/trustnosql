<?php

use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User as Model;

Model::where(1)->delete();

$users = test_seed_users();

foreach ($users as $user) {
    Model::create($user);
}
