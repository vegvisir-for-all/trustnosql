<?php

namespace Vegvisir\TrustNoSql\Commands\Team;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Commands\BaseCommand;
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Models\Permission;
use Vegvisir\TrustNoSql\Models\Team;

class Info extends BaseCommand
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:team:info';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Shows a detailed information about the team';

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
        $teamNames = $this->getEntitiesList(new Team);

        foreach($teamNames as $teamName) {
            $this->line("Showing information for team '$teamName'");

            $team = $this->getTeam($teamName, true);

            /**
             * 0. Info
             */

            $this->line("Name: $teamName");
            $this->line("Display name: $team->display_name");
            $this->line("Description: $team->description");

        }
    }
}
