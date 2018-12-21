<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * (c) Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 *
 * This source file is subject to the GPL-3.0-or-later license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vegvisir\TrustNoSql\Helpers;

use Illuminate\Support\Facades\Config;

class UserHelper extends HelperProxy
{
    /**
     * Gets a user model used by application.
     *
     * @return Jenssegers\Mongodb\Eloquent\Model
     */
    public static function getModel()
    {
        $userModel = Config::get('laratrust.user_models.users');

        return new $userModel();
    }

    /**
     * Provide a user logic proxy for middleware checking.
     *
     * @param User $user
     *
     * @return Closure
     */
    public static function logicProxy($user)
    {
        return function ($middlewareNamespace, $entityName) use ($user) {
            if (null === $user) {
                return false;
            }

            $functionName = 'has'.ucfirst($middlewareNamespace);

            return $user->{$functionName}($entityName);
        };
    }

    /**
     * Checks whether an object is a user model.
     *
     * @param object $object Object to be checked
     *
     * @return bool
     */
    protected static function isOne($object)
    {
        $userModel = static::getModel();

        return is_a(\get_class($object), \get_class(new $userModel()), true);
    }
}
