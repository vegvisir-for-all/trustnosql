<?php

namespace Vegvisir\TrustNoSql\Helpers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Support\Facades\Config;
use Jenssegers\Mongodb\Eloquent\Model;

class UserHelper
{

    /**
     * Gets a user model used by application or false if given model
     * is not an instance of Jenssegers\Mongodb\Eloquent\Model
     *
     * @return Jenssegers\Mongodb\Eloquent\Model|false
     */
    protected static function getModel()
    {
        $userModel = Config::get('laratrust.user_models.user');
        if(is_a($userModel, Model::class(), true)) {
            return new $userModel;
        } else {
            return false;
        }
    }

}
