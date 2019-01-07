<?php

use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User as Model;

if (null == Cache::get('create_users')) {
    Model::where(1)->delete();

    $users = test_seed_users();

    foreach ($users as $user) {
        Model::create($user);
    }
    Cache::put('create_users', 2);
}
