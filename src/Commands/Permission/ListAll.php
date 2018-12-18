<?php

namespace Vegvisir\TrustNoSql\Commands\Permission;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Commands\BaseCommand;
use Vegvisir\TrustNoSql\Models\Permission;

class ListAll extends BaseCommand
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:permissions';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Shows a list of all permissions';

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
        $permissions = collect(Permission::all([
            'id', 'name', 'display_name', 'description'
        ]))->toArray();

        $headers = [
            '_id', 'Name', 'Display name', 'Description'
        ];

        $this->table($headers, $permissions);
    }
}
