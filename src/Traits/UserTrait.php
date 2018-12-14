<?php

namespace Vegvisir\TrustNoSql\Traits;

trait UserTrait
{

    public function roles()
    {
        $roles = $this->belongsTo(\Vegvisir\TrustNoSql\Models\Role::class);

        return $roles;
    }

    public function rolesTeams()
    {

    }

    public function permissions()
    {

    }

    public function hasRole()
    {

    }

    public function hasPermission()
    {

    }

}
