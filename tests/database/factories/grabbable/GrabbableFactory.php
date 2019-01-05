<?php

use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;

Permission::where(1)->delete();
Role::where(1)->delete();
Team::where(1)->delete();
