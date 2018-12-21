<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

namespace Vegvisir\TrustNoSql\Commands\Team;

use Vegvisir\TrustNoSql\Commands\BaseAttach;
use Vegvisir\TrustNoSql\Models\Team;

class Attach extends BaseAttach
{
    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:team:attach';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Attach a TrustNoSql team to user(s) and role(s)';

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

        if ($this->confirm('Do you want to attach team(s) to user(s)?', true)) {
            $askAbout[] = 'users';
        }

        if ($this->confirm('Do you want to attach team(s) to role(s)?', true)) {
            $askAbout[] = 'roles';
        }

        if (empty($askAbout)) {
            $this->error('Sorry, can\'t help');

            return;
        }

        $this->entityAttach(new Team(), $askAbout);
    }
}
