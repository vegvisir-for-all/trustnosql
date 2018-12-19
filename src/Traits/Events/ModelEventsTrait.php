<?php

namespace Vegvisir\TrustNoSql\Traits\Events;

use Vegvisir\TrustNoSql\Observers\ObserverProxy;

trait ModelEventsTrait
{

    public static function bootTrustNoSqlEvents()
    {
        static::trustNoSqlObserve(ObserverProxy::getModelObserver(__CLASS__));
    }

    protected static function trustNoSqlObserve($observer = null)
    {
        $observerName = is_string($observer) ? $observer : get_class($observer);

        foreach(self::$trustNoSqlObservables as $event) {
            static::registerTrustNoSqlEvent(snake_case($event, '.'), $observerName.'@'.$event);
        }
    }

    protected function fireTrustNoSqlEvent($event, $payload = [])
    {
        if(!isset(static::$dispatcher)) {
            return true;
        }

        return static::$dispatcher->fire(
            "trustnosql.{$event}: ".static::class,
            $payload
        );
    }

    protected static function registerTrustNoSqlEvent($eventName, $callback)
    {
        if (isset(static::$dispatcher)) {
            $name = static::class;
            static::$dispatcher->listen("trustnosql.{$eventName}: {$name}", $callback);
        }
    }
}
