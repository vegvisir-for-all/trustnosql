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

use Vegvisir\TrustNoSql\TrustNoSql;
use Vegvisir\TrustNoSql\Tests\TestCase;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\Top as GrabbableTop;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\Middle as GrabbableMiddle;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\Bottom as GrabbableBottom;

class GrabbableTest extends TestCase
{
    public function testIsGrabbable() {
        $top = new GrabbableTop;
        $middle = new GrabbableMiddle;
        $bottom = new GrabbableBottom;
        $this->assertTrue(is_a($top, \Vegvisir\TrustNoSql\Models\Grabbable::class));
        $this->assertTrue(is_a($middle, \Vegvisir\TrustNoSql\Models\Grabbable::class));
        $this->assertTrue(is_a($bottom, \Vegvisir\TrustNoSql\Models\Grabbable::class));
    }

    public function testHasMethods() {
        /**
         * Methods:
         * 1. setGrababilityMode
         * 2. explicitelyGrabbedBy
         * 3. grabbableBy
         */

        $grabbables = [
            'top' => new GrabbableTop,
            'middle' => new GrabbableMiddle,
            'bottom' => new GrabbableBottom,
        ];

        $methods = [
            'setGrababilityMode',
            'explicitelyGrabbedBy',
            'grabbableBy'
        ];

        foreach($grabbables as $grabbableName => $grabbableObj) {
            foreach($methods as $methodName) {
                $this->assertTrue(method_exists(get_class($grabbableObj), $methodName));
            }
        }
    }

    public function testRulesOverwritten() {}

    public function testModeNone() {}

    public function testModeExplicit() {}

    public function testModeGrabbable() {}

    public function testModeBoth() {}
}
