<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * (c) Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 *
 * This source file is subject to the GPL-3.0-or-later license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Vegvisir\TrustNoSql\Commands\Role;

use Vegvisir\TrustNoSql\Commands\BaseDelete;
use Vegvisir\TrustNoSql\Models\Role;

class Delete extends BaseDelete
{
    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:role:delete';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Delete a TrustNoSql role';

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
        $this->entityDelete(new Role());
    }
}
