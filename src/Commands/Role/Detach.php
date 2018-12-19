<?php

namespace Vegvisir\TrustNoSql\Commands\Role;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Commands\BaseDetach;
use Vegvisir\TrustNoSql\Models\Role;

class Detach extends BaseDetach
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:role:detach';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Detach a TrustNoSql role from user(s)';

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

        if($this->confirm('Do you want to detach roles from user(s)?', true)) {
            $askAbout[] = 'users';
        }

        if(empty($askAbout)) {
            $this->error('Sorry, can\'t help');
            return;
        }

        $this->entityDetach(new Role, $askAbout);
    }
}
