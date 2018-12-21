<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Models;

use Illuminate\Support\Facades\Config;
use Jenssegers\Mongodb\Eloquent\Model;
use Vegvisir\TrustNoSql\Contracts\PermissionInterface;
use Vegvisir\TrustNoSql\Traits\PermissionTrait;

class Permission extends Model implements PermissionInterface
{
    use PermissionTrait;

    /**
     * Collection used by model.
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
        'description',
    ];

    /**
     * Creates new instance of the model.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (null !== ($collectionName = Config::get('trustnosql.collections.permissions'))) {
            $this->collection = $collectionName;
        }
    }
}
