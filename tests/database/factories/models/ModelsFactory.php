<?php

use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;

Permission::where(1)->delete();

$permissionsData = [
    [
        'name' => 'permission/first',
        'display_name' => 'Permission First',
        'description' => 'Permission First'
    ],
    [
        'name' => 'permission/second',
        'display_name' => 'Permission Second',
        'description' => 'Permission Second'
    ],
    [
        'name' => 'permission/third',
        'display_name' => 'Permission Third',
        'description' => 'Permission Third'
    ],
    [
        'name' => 'permission/fourth',
        'display_name' => 'Permission Fourth',
        'description' => 'Permission Fourth'
    ],
];

foreach ($permissionsData as $permissionData) {
    Permission::create($permissionData);
}

Role::where(1)->delete();

$rolesData = [
    [
        'name' => 'role-first',
        'display_name' => 'Role First',
        'description' => 'Role First'
    ],
    [
        'name' => 'role-second',
        'display_name' => 'Role Second',
        'description' => 'Role Second'
    ],
    [
        'name' => 'role-third',
        'display_name' => 'Role Third',
        'description' => 'Role Third'
    ],
    [
        'name' => 'role-fourth',
        'display_name' => 'Role Fourth',
        'description' => 'Role Fourth'
    ],
];

foreach ($rolesData as $roleData) {
    Role::create($roleData);
}

Team::where(1)->delete();

$teamsData = [
    [
        'name' => 'team-first',
        'display_name' => 'Team First',
        'description' => 'Team First'
    ],
    [
        'name' => 'team-second',
        'display_name' => 'Team Second',
        'description' => 'Team Second'
    ],
    [
        'name' => 'team-third',
        'display_name' => 'Team Third',
        'description' => 'Team Third'
    ],
    [
        'name' => 'team-fourth',
        'display_name' => 'Team Fourth',
        'description' => 'Team Fourth'
    ],
];

foreach ($teamsData as $teamData) {
    Team::create($teamData);
}
