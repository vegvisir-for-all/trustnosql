<?php

namespace Vegvisir\TrustNoSql\Models;

/**
 * This file is part of TrustNoSql
 * a MongoDB role/permission management for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Support\Facades\Config;
use Jenssegers\Mongodb\Eloquent\Model;
use Vegvisir\TrustNoSql\Contracts\PermissionInterface;
use Vegvisir\TrustNoSql\Traits\PermissionTrait;

class Permission extends Model implements PermissionInterface
{

    use PermissionTrait;

    /**
     * Collection used by model
     *
     * @var string
     */
    protected $collection = 'permissions';

    /**
     * Fillable fields of the model.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'display_name',
        'description'
    ];

    /**
     * Creates new instance of the model
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if(null !== ($collectionName = Config::get('trustnosql.collections.permissions'))) {
            $this->collection = $collectionName;
        }

    }

}
