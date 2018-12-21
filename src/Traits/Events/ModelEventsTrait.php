<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Traits\Events;

use Closure;
use Vegvisir\TrustNoSql\Observers\ObserverProxy;

trait ModelEventsTrait
{
    /**
     * Boots TrustNoSql events for model.
     */
    public static function bootTrustNoSqlEvents()
    {
        static::trustNoSqlObserve(ObserverProxy::getModelObserver(__CLASS__));
    }

    /**
     * Register observables.
     *
     * @param object $observer
     */
    protected static function trustNoSqlObserve($observer = null)
    {
        $observerName = \is_string($observer) ? $observer : \get_class($observer);

        foreach (self::$trustNoSqlObservables as $event) {
            static::registerTrustNoSqlEvent(snake_case($event, '.'), $observerName.'@'.$event);
        }
    }

    /**
     * Register single TrustNoSqlEvent.
     *
     * @param string  $eventName
     * @param Closure $callback
     */
    protected static function registerTrustNoSqlEvent($eventName, $callback)
    {
        if (isset(static::$dispatcher)) {
            $name = static::class;
            static::$dispatcher->listen("trustnosql.{$eventName}: {$name}", $callback);
        }
    }

    /**
     * Fire a TrustNoSql event.
     *
     * @param string $event   Event name
     * @param array  $payload Payload
     *
     * @return mixed
     */
    protected function fireTrustNoSqlEvent($event, $payload = [])
    {
        if (!isset(static::$dispatcher)) {
            return true;
        }

        return static::$dispatcher->fire(
            "trustnosql.{$event}: ".static::class,
            $payload
        );
    }
}
