<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Tests\Grabbable;

use Vegvisir\TrustNoSql\TrustNoSql;
use Vegvisir\TrustNoSql\Tests\TestCase;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\Top;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\Middle;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\Bottom;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeNone;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeExplicit;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeGrabbable;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeBoth;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeEither;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

class GrabbableTest extends GrabbableTestCase
{
    public function testCreated()
    {
        $grabbablesData = [
            'bottom',
            'middle',
            'modeBoth',
            'modeEither',
            'modeExplicit',
            'modeGrabbable',
            'modeNone',
            'top',
        ];

        foreach ($grabbablesData as $grabbableName) {
            $grabbableClassName = "\\Vegvisir\\TrustNoSql\\Tests\\Infrastructure\\Grabbables\\" . ucfirst($grabbableName);

            $grabbable = $grabbableClassName::where('name', kebab_case($grabbableName))->first();
            $this->assertEquals(kebab_case($grabbableName), $grabbable->name);
        }
    }

    public function testIsGrabbable()
    {
        $top = new Top;
        $middle = new Middle;
        $bottom = new Bottom;
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
            'top' => Top::where('name', 'top')->first(),
            'middle' => Middle::where('name', 'middle')->first(),
            'bottom' => Bottom::where('name', 'bottom')->first(),
        ];

        $methods = [
            'setGrababilityMode',
            'explicitelyGrabbedBy'
        ];

        foreach ($grabbables as $grabbableName => $grabbableObj) {
            foreach ($methods as $methodName) {
                $this->assertTrue(method_exists(get_class($grabbableObj), $methodName));
            }
        }
    }

    public function testModeNone()
    {
        $modeNone = ModeNone::where('name', 'mode-none')->first();
        $this->assertEquals($modeNone::MODE_NONE, $modeNone->getGrababilityMode());

        $user = User::where(1)->first();

        $this->assertTrue($modeNone->canBeGrabbedBy($user));
    }

    public function testModeExplicit()
    {
        $modeExplicit = ModeExplicit::where('name', 'mode-explicit')->first();
        $this->assertEquals($modeExplicit::MODE_EXPLICIT, $modeExplicit->getGrababilityMode());
        $user = User::where(1)->first();
        $this->assertTrue($modeExplicit->canBeGrabbedBy($user));
    }

    public function testModeGrabbable()
    {
        $modeGrabbable = ModeGrabbable::where('name', 'mode-grabbable')->first();
        $this->assertEquals($modeGrabbable::MODE_GRABBABLE, $modeGrabbable->getGrababilityMode());

        $user = User::where(1)->orderBy('_id', 'desc')->first();
        $this->assertTrue($modeGrabbable->canBeGrabbedBy($user));

        $user = User::where(1)->orderBy('_id', 'asc')->first();
        $this->assertFalse($modeGrabbable->canBeGrabbedBy($user));
    }

    public function testModeBoth()
    {
        $modeBoth = ModeBoth::where('name', 'mode-both')->first();
        $this->assertEquals($modeBoth::MODE_BOTH, $modeBoth->getGrababilityMode());

        $first = User::where(1)->first();
        $last = User::where(1)->orderBy('_id', 'desc')->first();

        $this->assertTrue($modeBoth->canBeGrabbedBy($first));
        $this->assertFalse($modeBoth->canBeGrabbedBy($last));
    }

    public function testModeEither()
    {
        $modeEither = new ModeEither;
        $this->assertEquals($modeEither::MODE_EITHER, $modeEither->getGrababilityMode());

        $first = User::where(1)->first();
        $last = User::where(1)->orderBy('_id', 'desc')->first();

        $this->assertTrue($modeEither->canBeGrabbedBy($first));
        $this->assertTrue($modeEither->canBeGrabbedBy($last));
    }
}
