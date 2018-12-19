<?php

namespace Vegvisir\TrustNoSql\Commands\Team;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Commands\BaseDetach;
use Vegvisir\TrustNoSql\Models\Team;

class Detach extends BaseDetach
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:team:detach';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Detach a TrustNoSql team from role(s) or user(s)';

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

        if($this->confirm('Do you want to detach team(s) from role(s)?', true)) {
            $askAbout[] = 'roles';
        }

        if($this->confirm('Do you want to detach team(s) from user(s)?', true)) {
            $askAbout[] = 'users';
        }

        if(empty($askAbout)) {
            $this->error('Sorry, can\'t help');
            return;
        }

        $this->entityDetach(new Team, $askAbout);
    }
}
