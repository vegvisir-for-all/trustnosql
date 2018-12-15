<?php

namespace Vegvisir\TrustNoSql\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Helpers\Helper;
use Vegvisir\TrustNoSql\Models\Permission;
use Vegvisir\TrustNoSql\Models\Role;
use Vegvisir\TrustNoSql\Models\Team;
use Vegvisir\TrustNoSql\Traits\Commands\ErrorTrait;
use Vegvisir\TrustNoSql\Traits\Commands\SuccessTrait;

class BaseCommand extends Command
{

    protected function getPermission($permissionName, $shouldExist)
    {
        $permission = Permission::where('name', $permissionName)->first();

        if($permission == null) {
            if($shouldExist) {
                $this->doesNotExist('permission', $permissionName);
                return false;
            } else {
                return false;
            }
        } elseif(!$shouldExist) {
            $this->alreadyExists('permission', $permissionName);
            return false;
        }

        return $permission;
    }

    protected function getRole($roleName, $shouldExist)
    {
        $role = Role::where('name', $roleName)->first();

        if($role == null) {
            if($shouldExist) {
                $this->doesNotExist('role', $roleName);
                return false;
            } else {
                return false;
            }
        } elseif(!$shouldExist) {
            $this->alreadyExists('role', $roleName);
            return false;
        }

        return $role;
    }

    protected function getTeam($teamName, $shouldExist)
    {
        $team = Team::where('name', $teamName)->first();

        if($team == null) {
            if($shouldExist) {
                $this->doesNotExist('team', $teamName);
                return false;
            } else {
                return false;
            }
        } elseif(!$shouldExist) {
            $this->alreadyExists('team', $teamName);
            return false;
        }

        return $team;
    }

    protected function getUser($email, $shouldExist)
    {
        $userModel = Helper::getUserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user == null) {
            if ($shouldExist) {
                $this->doesNotExist('user', $email);
                return false;
            }
            return true;
        } elseif (!$shouldExist) {
            $this->alreadyExists('user', $email);
            return false;
        }

        return $user;
    }

    protected function isTeamFunctionalityOn($shouldBeOn)
    {
        // Checking if team functionality is on
        if (Config::get('laratrust.use_teams')) {

            if(!$shouldBeOn) {
                $this->noTeamFunctionality();
                return false;
            }

            return true;
        } else {

            if($shouldBeOn) {
                $this->errorTeamFunctionality();
                return false;
            }

            return true;
        }
    }
}
