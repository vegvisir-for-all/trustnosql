<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User as Model;

if (null === Cache::get('create_users')) {
    Model::where(1)->delete();

    $users = test_seed_users();

    foreach ($users as $user) {
        Model::create($user);
    }
    Cache::put('create_users', 2);
}
