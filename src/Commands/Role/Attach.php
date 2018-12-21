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

use Vegvisir\TrustNoSql\Commands\BaseAttach;
use Vegvisir\TrustNoSql\Models\Role;

class Attach extends BaseAttach
{
    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:role:attach';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Attach a TrustNoSql role to user(s)';

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
        $askAbout = [];

        if ($this->confirm('Do you want to attach roles to user?', true)) {
            $askAbout[] = 'users';
        }

        if (empty($askAbout)) {
            $this->error('Sorry, can\'t help');

            return;
        }

        $this->entityAttach(new Role(), $askAbout);
    }
}
