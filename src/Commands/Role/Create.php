<?php

namespace Vegvisir\TrustNoSql\Commands\Role;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
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
        $this->entityCreate(new Role);
    }
}
