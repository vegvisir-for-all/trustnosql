<?php

namespace Vegvisir\TrustNoSql\Commands\Permission;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Commands\BaseAttach;
use Vegvisir\TrustNoSql\Models\Permission;
use Vegvisir\TrustNoSql\Models\Role;

class Attach extends BaseAttach
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:permission:attach';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Attach a TrustNoSql permission to role(s) and/or user(s)';

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

        if($this->confirm('Do you want to attach permissions to roles?', true)) {
            $askAbout[] = 'roles';
        }

        if($attachToUsers = $this->confirm('Do you want to attach permissions explicitely to users?', false)) {
            $askAbout[] = 'users';
        }

        if(empty($askAbout)) {
            $this->error('Sorry, can\'t help');
            return;
        }

        $this->entityAttach(new Permission, $askAbout);

    }

}
