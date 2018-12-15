<?php

namespace Vegvisir\TrustNoSql\Helpers;

use Illuminate\Support\Facades\Config;
use Jenssegers\Mongodb\Eloquent\Model;

class UserHelper
{

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
