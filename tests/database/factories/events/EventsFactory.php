<?php

use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;

$permissionData = [
    'name' => 'permission/test',
    'display_name' => 'Permission Test',
    'description' => 'Permission Test'
];

Permission::create($permissionData);

$roleData = [
    'name' => 'role-test',
    'display_name' => 'Role Test',
    'description' => 'Role Test'
];

Role::create($roleData);

$teamData = [
    'name' => 'team-test',
    'display_name' => 'Team Test',
    'description' => 'Team Test'
];

Team::create($teamData);
