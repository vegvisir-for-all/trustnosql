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

use Vegvisir\TrustNoSql\Commands\BaseListAll;
use Vegvisir\TrustNoSql\Models\Role;

class ListAll extends BaseListAll
{
    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:roles';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Shows a list of all roles';

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
        $this->entitiesListAll(new Role());
    }
}
