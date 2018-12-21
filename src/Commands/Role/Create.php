<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Commands\Role;

use Vegvisir\TrustNoSql\Commands\BaseCreate;
use Vegvisir\TrustNoSql\Models\Role;

class Create extends BaseCreate
{
    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:role:create';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Create a new TrustNoSql role';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute a console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->entityCreate(new Role());
    }
}
