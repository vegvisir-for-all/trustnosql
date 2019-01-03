<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
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
        $userModel = Config::get('trustnosql.user_models.users', 'App\User');

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

    /**
     * Gets an array of users' keys (emails).
     *
     * @param array|string $users Comma-separated values or array
     *
     * @return array
     */
    protected static function getKeys($users)
    {
        if (!\is_array($users)) {
            $users = static::getArray($users);
        }

        return collect(self::getModel()->whereIn('email', (array) $users)->get())->map(function ($item, $key) {
            return $item->email;
        })->toArray();
    }
}
