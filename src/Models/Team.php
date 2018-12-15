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
use Vegvisir\TrustNoSql\Contracts\TeamInterface;
use Vegvisir\TrustNoSql\Traits\TeamTrait;

class Team extends Model implements TeamInterface
{

    use TeamTrait;

    /**
     * Collection used by model
     *
     * @var string
     */
    protected $collection = 'teams';

    /**
     * Creates new instance of the model
     *
     * @param array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if(null !== ($collectionName = Config::get('trustnosql.collections.teams'))) {
            $this->collection = $collectionName;
        }

    }

}
