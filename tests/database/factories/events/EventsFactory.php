<?php

use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;

Permission::where(1)->delete();

$permissionData = [
    'name' => 'permission/test',
    'display_name' => 'Permission Test',
    'description' => 'Permission Test'
];

Permission::create($permissionData);

Role::where(1)->delete();

$roleData = [
    'name' => 'role-test',
    'display_name' => 'Role Test',
    'description' => 'Role Test'
];

Role::create($roleData);

Team::where(1)->delete();

$teamData = [
    'name' => 'team-test',
    'display_name' => 'Team Test',
    'description' => 'Team Test'
];

Team::create($teamData);
