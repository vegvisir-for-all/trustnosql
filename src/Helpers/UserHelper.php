<?php

namespace Vegvisir\TrustNoSql\Helpers;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Support\Facades\Config;

class UserHelper extends HelperProxy
{

    /**
     * Gets a user model used by application
     *
     * @return Jenssegers\Mongodb\Eloquent\Model
     */
    public static function getModel()
    {
        $userModel = Config::get('laratrust.user_models.users');
        return new $userModel;
    }

    /**
     * Checks whether an object is a user model
     *
     * @param Object $object Object to be checked
     * @return bool
     */
    protected static function isOne($object)
    {
        $userModel = static::getModel();
        return is_a(get_class($object), get_class(new $userModel()), true);
    }

    /**
     * Provide a user logic proxy for middleware checking
     *
     * @param User $user
     * @return Closure
     */
    public static function logicProxy($user)
    {
        return function ($middlewareNamespace, $entityName) use ($user) {

            if($user == null) {
                return false;
            }

            $functionName = 'has' . ucfirst($middlewareNamespace);
            return $user->$functionName($entityName);
        };
    }

}
