<?php

namespace Vegvisir\TrustNoSql\Commands\Team;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Vegvisir\TrustNoSql\Commands\BaseCommand;
use Vegvisir\TrustNoSql\Models\Team;

class Create extends BaseCommand
{

    /**
     * The name of the signature in the console command.
     *
     * @var string
     */
    protected $signature = 'trustnosql:team:create';

    /**
     * Console command description.
     *
     * @var string
     */
    protected $description = 'Create a new TrustNoSql team';

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

        $keepAsking = true;

        while($keepAsking) {
            $teamName = $this->ask('Name of the team');

            /**
             * $keepAsking should change only when team doesn't exist
             * It should NOT change when team exist (also, an error should be displayed)
             */

             if($this->getTeam($teamName, false) == true) {
                 $keepAsking = false;
             }
        }

        $displayName = $this->ask('Display name', false);
        $description = $this->ask('Description', false);

        try {
            Team::create([
                'name' => $teamName,
                'display_name' => $displayName,
                'description' => $description
            ]);

            $this->successCreating('team', $teamName);
        } catch (\Exception $e) {
            $this->errorCreating('team', $teamName, $e->getMessage());
        }
    }
}
