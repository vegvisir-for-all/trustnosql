<?php

namespace Vegvisir\TrustNoSql\Commands\Role;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Commands\BaseCommand;
use Vegvisir\TrustNoSql\Models\Role;

class ListAll extends BaseCommand
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
        $roles = collect(Role::all([
            'id', 'name', 'display_name', 'description'
        ]))->toArray();

        $headers = [
            '_id', 'Name', 'Display name', 'Description'
        ];

        $this->table($headers, $roles);
    }
}