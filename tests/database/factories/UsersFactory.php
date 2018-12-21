<?php

use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User as Model;

$users = test_seed_users();

foreach($users as $user) {
    Model::create($user);
}
