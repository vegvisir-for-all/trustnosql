<?php

namespace Vegvisir\TrustNoSql\Commands;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Vegvisir\TrustNoSql\Helper;
use Vegvisir\TrustNoSql\Models\Permission;
use Vegvisir\TrustNoSql\Models\Role;
use Vegvisir\TrustNoSql\Models\Team;
use Vegvisir\TrustNoSql\Traits\Commands\ErrorCommandTrait;
use Vegvisir\TrustNoSql\Traits\Commands\SuccessCommandTrait;

class BaseCommand extends Command
{

    use ErrorCommandTrait, SuccessCommandTrait;

    /**
     * Function retrieves permission by its name. It can output an error message
     * if existence or non-existence of the permission is undesirable.
     *
     * @param string $permissionName Name of the permission.
     * @param bool $shouldExist Set to true if a permission should exist.
     */
    protected function getPermission($permissionName, $shouldExist)
    {
        $permission = Permission::where('name', $permissionName)->first();

        if($permission == null) {
            if($shouldExist) {
                $this->doesNotExist('permission', $permissionName);
                return false;
            } else {
                return true;
            }
        } elseif(!$shouldExist) {
            $this->alreadyExists('permission', $permissionName);
            return false;
        }

        return $permission;
    }

    /**
     * Function retrieves role by its name. It can output an error message
     * if existence or non-existence of the role is undesirable.
     *
     * @param string $roleName Name of the role.
     * @param bool $shouldExist Set to true if a permission should exist.
     */
    protected function getRole($roleName, $shouldExist)
    {
        $role = Role::where('name', $roleName)->first();

        if($role == null) {
            if($shouldExist) {
                $this->doesNotExist('role', $roleName);
                return false;
            } else {
                return true;
            }
        } elseif(!$shouldExist) {
            $this->alreadyExists('role', $roleName);
            return false;
        }

        return $role;
    }

    /**
     * Function retrieves team by its name. It can output an error message
     * if existence or non-existence of the team is undesirable.
     *
     * @param string $teamName Name of the team.
     * @param bool $shouldExist Set to true if a permission should exist.
     */
    protected function getTeam($teamName, $shouldExist)
    {
        $team = Team::where('name', $teamName)->first();

        if($team == null) {
            if($shouldExist) {
                $this->doesNotExist('team', $teamName);
                return false;
            } else {
                return true;
            }
        } elseif(!$shouldExist) {
            $this->alreadyExists('team', $teamName);
            return false;
        }

        return $team;
    }

    /**
     * Function retrieves user by its email. It can output an error message
     * if existence or non-existence of the user is undesirable.
     *
     * @param string $email E-mail address of the user.
     * @param bool $shouldExist Set to true if a permission should exist.
     */
    protected function getUser($email, $shouldExist)
    {
        $userModel = Helper::getUserModel();
        $user = $userModel->where('email', $email)->first();

        if ($user == null) {
            if ($shouldExist) {
                $this->doesNotExist('user', $email);
                return false;
            } else {
                return true;
            }
        } elseif (!$shouldExist) {
            $this->alreadyExists('user', $email);
            return false;
        }

        return $user;
    }

    /**
     * Function checks whether team functionality is set to on in the configs.
     * It can output an error message if current settings are undesirable.
     */
    protected function isTeamFunctionalityOn($shouldBeOn)
    {
        // Checking if team functionality is on
        if (Config::get('trustnosql.teams.use_teams')) {

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

    /**
     * Displays roles list and returns array of choices.
     *
     * @param string|null $question A question that should be asked
     * @param array|null $roles Optional array of roles to be displayed
     * @return array
     */
    protected function getEntitiesList($model, $question = null, $options = null)
    {

        $modelName = strtolower(class_basename($model));

        if($question == null) {
            $question = ucfirst($modelName) . ' list';
        }

        if($options == null) {
            $options = collect($model->all())->map(function ($item, $key) use($modelName) {
                return $modelName == 'user' ? 'email' : 'name';
            })->toArray();
        }

        if(empty($options)) {
            $this->error('No ' . $modelName . 's to choose. Sorry :(');
            die();
        }

        sort($options);

        $optionNames = $this->choice($question, $options, null, count($options), true);

        return $optionNames;
    }

}
