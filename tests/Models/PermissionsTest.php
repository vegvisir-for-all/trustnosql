<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests\Models;

use Vegvisir\TrustNoSql\Tests\TestCase;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;

class PermissionsTest extends TestCase
{
    public function testCreate() {

        $permission = Permission::create([
            'name' => 'namespace/task'
        ]);
        $this->assertSame('namespace/task', $permission->name);

        $permission = Permission::create([
            'name' => 'namespace/*'
        ]);
        $this->assertNull($permission);

        $permission = Permission::create([
            'name' => 'namespace-task'
        ]);
        $this->assertNull($permission);

    }
}
