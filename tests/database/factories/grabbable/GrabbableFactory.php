<?php

use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\Bottom;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\Middle;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeBoth;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeEither;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeExplicit;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeGrabbable;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\ModeNone;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Grabbables\Top;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Permission;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Role;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\Team;
use Vegvisir\TrustNoSql\Tests\Infrastructure\Models\User;

if (null == Cache::get('create_grabbables')) {
    Permission::where(1)->delete();
    Role::where(1)->delete();
    Team::where(1)->delete();

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

        $arguments = ['name' => kebab_case($grabbableName)];

        if ($grabbableName == 'modeGrabbable' || $grabbableName == 'modeBoth') {
            $ownerIds = [
            User::where(1)->first()->id,
            User::where(1)->orderBy('_id', 'desc')->first()->id
        ];

            $arguments['owner_ids'] = $ownerIds;
        }

        $grabbableClassName::create($arguments);
    }

    Cache::put('create_grabbables', true, 2);
}
