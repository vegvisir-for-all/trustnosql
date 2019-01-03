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
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\RulesOverwritten;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\RulesNotOverwritten;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeNone;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeExplicit;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeGrabbable;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeBoth;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeEither;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class GrabbableTest extends TestCase
{
    public function testIsGrabbable()
    {
        $top = new GrabbableTop;
        $middle = new GrabbableMiddle;
        $bottom = new GrabbableBottom;
        $this->assertTrue(is_a($top, \Vegvisir\TrustNoSql\Models\Grabbable::class));
        $this->assertTrue(is_a($middle, \Vegvisir\TrustNoSql\Models\Grabbable::class));
        $this->assertTrue(is_a($bottom, \Vegvisir\TrustNoSql\Models\Grabbable::class));
    }

    public function testHasMethods()
    {
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

        foreach ($grabbables as $grabbableName => $grabbableObj) {
            foreach ($methods as $methodName) {
                $this->assertTrue(method_exists(get_class($grabbableObj), $methodName));
            }
        }
    }

    public function testRulesOverwritten()
    {
        $rulesOverwritten = new RulesOverwritten;
        $this->assertTrue($rulesOverwritten->isGrababilityOverwritten());

        $rulesNotOverwritten = new RulesNotOverwritten;
        $this->assertFalse($rulesNotOverwritten->isGrababilityOverwritten());
    }

    public function testModeNone()
    {
        $modeNone = new ModeNone;
        $this->assertEquals($modeNone::MODE_NONE, $modeNone->getGrababilityMode());

        $user = User::where(1)->first();

        $this->assertTrue($modeNone->canBeGrabbedBy($user));
    }

    public function testModeExplicit()
    {
        $modeExplicit = new ModeExplicit;
        $this->assertEquals($modeExplicit::MODE_EXPLICIT, $modeExplicit->getGrababilityMode());

        // ---

        $modeExplicit->save();

        $this->assertTrue($modeExplicit->canBeGrabbedBy(User::where(1)->first()));
    }

    public function testModeGrabbable()
    {
        $modeGrabbable = new ModeGrabbable;
        $this->assertEquals($modeGrabbable::MODE_GRABBABLE, $modeGrabbable->getGrababilityMode());

        // ---

        $modeGrabbable->save();

        $user = User::where(1)->orderBy('_id', 'desc')->first();

        $this->assertTrue($modeGrabbable->canBeGrabbedBy($user));

        $user = User::where(1)->orderBy('_id', 'asc')->first();

        $this->assertFalse($modeGrabbable->canBeGrabbedBy($user));
    }

    public function testModeBoth()
    {
        $modeBoth = new ModeBoth;
        $this->assertEquals($modeBoth::MODE_BOTH, $modeBoth->getGrababilityMode());

        // ---

        $modeBoth->save();

        $first = User::where(1)->first();
        $last = User::where(1)->orderBy('_id', 'desc')->first();

        $this->assertFalse($modeBoth->canBeGrabbedBy($first));
        $this->assertTrue($modeBoth->canBeGrabbedBy($last));
    }

    public function testModeEither()
    {
        $modeEither = new ModeEither;
        $this->assertEquals($modeEither::MODE_EITHER, $modeEither->getGrababilityMode());

        // ---

        $modeEither->save();

        $first = User::where(1)->first();
        $last = User::where(1)->orderBy('_id', 'desc')->first();

        $this->assertTrue($modeEither->canBeGrabbedBy($first));
        $this->assertTrue($modeEither->canBeGrabbedBy($last));
    }
}
