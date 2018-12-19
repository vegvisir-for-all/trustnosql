<?php

namespace Vegvisir\TrustNoSql\Traits;

use Vegvisir\TrustNoSql\Checkers\CheckProxy;

trait ModelTrait
{

    protected $namespaces = [
        'has',
        'attach',
        'detach',
        'sync'
    ];

    protected $entities = [
        'permissions',
        'roles',
        'teams'
    ];

    public function __call($name, $arguments)
    {

        $originalName = $name;

        $name = explode('_', snake_case($name));

        if(in_array($name[0], $this->namespaces) && in_array(str_plural($name[1]), $this->entities)) {
            return $this->hasEntities($name[1], $arguments[0], isset($arguments[1]) ? $arguments[1] : false);
        }

        return parent::__call($originalName, $arguments);
    }

    /**
     * Returns the right checker for the User model.
     *
     * @return \Vegvisir\TrustNoSql\Checkers\User\UserChecker;
     */
    protected function modelChecker()
    {
        return (new CheckProxy($this))->getChecker();
    }

    protected function hasEntities($entityModelName, $entityList, $requireAll)
    {

        $functionName = 'current' . ucfirst(class_basename($this)) . 'Has' . ucfirst(str_plural(($entityModelName)));
        return $this->modelChecker()->$functionName($entityList, $requireAll);
    }

}
