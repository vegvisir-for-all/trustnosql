<?php

namespace Vegvisir\TrustNoSql\Models;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Support\Facades\Config;
use Jenssegers\Mongodb\Eloquent\Model;
use Vegvisir\TrustNoSql\Contracts\RoleInterface;
use Vegvisir\TrustNoSql\Traits\RoleTrait;

class Role extends Model implements RoleInterface
{

    use RoleTrait;

    /**
     * Collection used by model
     *
     * @var string
     */
    protected $collection = 'roles';

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

        if(null !== ($collectionName = Config::get('trustnosql.collections.roles'))) {
            $this->collection = $collectionName;
        }

    }

}
