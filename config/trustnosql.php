<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018 Vegvisir Sp. z o.o.
 * @license GNU General Public License, version 3
 */

return [
    /*
    |--------------------------------------------------------------------------
    | TrustNoSql Cache
    |--------------------------------------------------------------------------
    |
    | This configuration helps to customize the TrustNoSql cache behavior.
    |
    */
    'cache' => [
        // Should TrustNoSql use cache functionality
        'use_cache' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | TrustNoSql CLI
    |--------------------------------------------------------------------------
    |
    | This configuration helps to customize the TrustNoSql command line interface behavior.
    |
    */
    'cli' => [
        // Should TrustNoSql use CLI
        'use_cli' => true,

        // CLI default signature
        'signature' => 'trustnosql.',
    ],

    /*
    |--------------------------------------------------------------------------
    | TrustNoSql collections
    |--------------------------------------------------------------------------
    |
    | These are the collection names used by TrustNoSql to store all the authorization data.
    |
    */
    'collections' => [
        // Roles collection.
        'roles' => 'roles',

        // Permissions collection.
        'permissions' => 'permissions',

        // Teams collection.
        'teams' => 'teams',

        // Users collection.
        'users' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | TrustNoSql events
    |--------------------------------------------------------------------------
    |
    | These are the configuration settings used by TrustNoSql to enhance events behaviour.
    |
    */
    'events' => [
        // Whether to use events
        'use_events' => true,

        // Observers list
        'observers' => [
            // Observer for Permission model
            'Permission' => \Vegvisir\TrustNoSql\Observers\PermissionObserver::class,

            // Observer for Role model
            'Role' => \Vegvisir\TrustNoSql\Observers\RoleObserver::class,

            // Observer for Team model
            'Team' => \Vegvisir\TrustNoSql\Observers\TeamObserver::class,

            // Observer for application user model
            'User' => \Vegvisir\TrustNoSql\Observers\UserObserver::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | TrustNoSql Middleware
    |--------------------------------------------------------------------------
    |
    | This configuration helps to customize the TrustNoSql middleware behavior.
    |
    */
    'middleware' => [
        // Define if the TrustNoSql middleware are registered automatically in the service provider
        'register' => true,

        /*
         * Method to be called in the middleware return case.
         * Available: abort|redirect
         */
        'handling' => 'abort',

        /*
         * Handlers for the unauthorized method in the middlewares.
         * The name of the handler must be the same as the handling.
         */
        'handlers' => [
            // Aborts the execution with a 403 code.
            'abort' => [
                'code' => 403,
            ],

            /*
             * Redirects the user to the given url.
             * If a message is needed to show, set the type and the message content.
             * If the message content is empty it won't be added to the redirection.
             */
            'redirect' => [
                'url' => '/home',
                'message' => [
                    'type' => 'error',
                    'content' => '',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | TrustNoSql Permissions
    |--------------------------------------------------------------------------
    |
    | This configuration helps to customize the TrustNoSql permissions behavior.
    |
    */
    'permissions' => [
        // Wildcards list
        'wildcards' => [
            '*',
            'all',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | TrustNoSql Teams
    |--------------------------------------------------------------------------
    |
    | This configuration helps to customize the TrustNoSql teams behavior.
    |
    */
    'teams' => [
        // Should TrustNoSql use team functionality
        'use_teams' => true,

        /*
         * Handling of the team functionality. Two options are possible:
         *
         * 1. explicit - team name(s) must be excplicitely enlisted in the middleware function,
         *               team isn't attached to role in any way
         * 2. auto - both roles and users have teams attached to, and while checking for a role,
         *           also a checking if user has a role attached to is performed
         */
        // 'handling' => 'explicit'
    ],

    /*
    |--------------------------------------------------------------------------
    | TrustNoSql User Models
    |--------------------------------------------------------------------------
    |
    | This is the array that contains the information of the user models.
    | This information is used in the add-trait command, and for the roles and
    | permissions relationships with the possible user models.
    |
    | The key in the array is the name of the relationship inside the roles and permissions.
    |
    */
    'user_models' => [
        // Role model
        'users' => 'App\User',
    ],
];
