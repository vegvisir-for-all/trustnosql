<?php

namespace Vegvisir\TrustNoSql\Contracts;

/**
 * This file is part of TrustNoSql,
 * a role/permission/team MongoDB management solution for Laravel.
 *
 * @license GPL-3.0-or-later
 */
interface UserInterface
{

    /**
     * BelongsToMany relations with Role.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function roles();

    /**
     * BelongsToMany relations with Permission.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function permissions();

    /**
     * BelongsToMany relations with Teams.
     *
     * @return \Jenssegers\Mongodb\Relations\BelongsToMany
     */
    public function teams();

    /**
     * Checks if the user has a role by its name.
     *
     * @param  string|array  $name       Role name or array of role names.
     * @param  string|bool   $team      Team name.
     * @param  bool          $requireAll All roles in the array are required.
     * @return bool
     */
    public function hasRole($name, $team = null, $requireAll = false);

    /**
     * Check if user has a permission by its name.
     *
     * @param  string|array  $permission Permission string or array of permissions.
     * @param  string|bool  $team      Team name.
     * @param  bool  $requireAll All roles in the array are required.
     * @return bool
     */
    public function hasPermission($permission, $team = null, $requireAll = false);

    /**
     * Check if user is a member of team by its name.
     *
     * @param string|array $team Team name or array of teams names
     * @param bool $checkAll User must be a member of all given teams.
     * @return bool
     */
    public function memberOf($team, $checkAll = false);

    /**
     * Checks role(s) and permission(s).
     *
     * @param  string|array  $roles       Array of roles or comma separated string
     * @param  string|array  $permissions Array of permissions or comma separated string.
     * @param  string|bool  $team      Team name.
     * @param  array|bool  $options     require_all_roles (true|false) or require_all_permissions (true|false) or require_all_teams (true|false)
     * @throws \InvalidArgumentException
     * @return \Vegvisir\TrustNoSql\Models\Ability
     */
    public function ability($roles, $permissions, $team = null, $options = [],
        $requireAllRoles = false, $requireAllPermissions = false, $requireAllTeams = false);

    /**
     * Alias to moloquent belongs-to-many relation's attach() method.
     *
     * @param  string  $role Role name.
     * @param  string|bool  $team      Team name.
     * @return static
     */
    public function attachRole($role, $team = null);

    /**
     * Alias to moloquent belongs-to-many relation's detach() method.
     *
     * @param  string  $role Role name.
     * @param  string|bool  $team      Team name.
     * @return static
     */
    public function detachRole($role, $team = null);

    /**
     * Attach multiple roles to a user.
     *
     * @param  string|array  $role Array of roles or comma separated string
     * @param  string|bool  $team      Team name.
     * @return static
     */
    public function attachRoles($roles, $team = null);

    /**
     * Detach multiple roles from a user.
     *
     * @param  string|array  $roles Array of roles or comma separated string
     * @param  string|bool  $team      Team name.
     * @return static
     */
    public function detachRoles($roles, $team = null);

    /**
     * Sync roles to the user.
     *
     * @param  string|array  $roles Array of roles or comma separated string
     * @param  string|bool  $team      Team name.
     * @return static
     */
    public function syncRoles($roles, $team = null);

    /**
     * Alias to moloquent belongs-to-many relation's attach() method.
     *
     * @param  string  $permission Permission name.
     * @param  string|bool  $team      Team name.
     * @return static
     */
    public function attachPermission($permission, $team = null);

    /**
     * Alias to moloquent belongs-to-many relation's detach() method.
     *
     * @param  string  $permission Permission name.
     * @param  string|bool  $team      Team name.
     * @return static
     */
    public function detachPermission($permission, $team = null);

    /**
     * Attach multiple permissions to a user.
     *
     * @param  string|array  $permissions Array of permissions or comma separated string
     * @param  string|bool  $team      Team name.
     * @return static
     */
    public function attachPermissions($permissions, $team = null);

    /**
     * Detach multiple permissions from a user.
     *
     * @param  string|array  $permissions Array of permissions or comma separated string
     * @param  string|bool  $team      Team name.
     * @return static
     */
    public function detachPermissions($permissions, $team = null);

    /**
     * Sync permissions to the user.
     *
     * @param  array  $permissions
     * @return static
     */
    public function syncPermissions($permissions, $team = null);

    /**
     * Checks if the user has access to the thing.
     *
     * @param  Object  $thing
     * @return boolean
     */
    public function reaches($thing);

    /**
     * Checks if the user has some role and if he can access the thing.
     *
     * @param  string|array  $role
     * @param  Object  $thing
     * @param  array  $options
     * @return boolean
     */
    public function hasRoleAndReaches($role, $thing, $options = []);

    /**
     * Checks if the user can do something and if he can access the thing.
     *
     * @param  string|array  $permission
     * @param  Object  $thing
     * @param  array  $options
     * @return boolean
     */
    public function canAndCanAccess($permission, $thing, $options = []);

    /**
     * Return all the user permissions.
     */
    public function allPermissions();

}
