<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;

if (null === Cache::get('create_events')) {
    Permission::where(1)->delete();

    $permissionData = [
        'name' => 'permission/test',
        'display_name' => 'Permission Test',
        'description' => 'Permission Test',
    ];

    Permission::create($permissionData);

    Role::where(1)->delete();

    $roleData = [
        'name' => 'role-test',
        'display_name' => 'Role Test',
        'description' => 'Role Test',
    ];

    Role::create($roleData);

    Team::where(1)->delete();

    $teamData = [
        'name' => 'team-test',
        'display_name' => 'Team Test',
        'description' => 'Team Test',
    ];

    Team::create($teamData);
    Cache::put('create_events', true, 2);
}
