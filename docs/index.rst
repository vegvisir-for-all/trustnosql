##########
TrustNoSql
##########

# .. image:: http://img.shields.io/github/release/vegvisir/trustnosql.svg :target: https://packagist.org/packages/vegvisir/trustnosql :alt: Latest Stable Version .. image:: http://img.shields.io/packagist/dm/vegvisir/trustnosql.svg :target: https://packagist.org/packages/vegvisir/trustnosql :alt: Total Downloads .. image:: https://api.travis-ci.org/vegvisir-for-all/trustnosql.svg?branch=master :target: https://travis-ci.org/vegvisir-for-all/trustnosql :alt: Build Status .. image:: http://img.shields.io/coveralls/vegvisir-for-all/trustnosql.svg?branch=master :target: https://coveralls.io/r/vegvisir-for-all/trustnosql?branch=master :alt: Coverage Status .. image:: https://github.styleci.io/repos/161784926/shield?branch=master :target: https://github.styleci.io/repos/161784926 :alt: StyleCI .. image:: https://readthedocs.org/projects/trustnosql/badge/?version=latest :target: https://trustnosql.readthedocs.io/en/latest/?badge=latest :alt: Documentation Status

Introduction
############

What is TrustNoSql
==================

TrustNoSql is a Laravel (>=5.4) role/permission manager for MongoDB-based applications. It supports roles, permissions, and teams, and offers a nice CLI for your convenience. TrustNoSql is built atop a great `jenssegers/laravel-mongodb <https://github.com/jenssegers/laravel-mongodb>`_ package, and it's inspired by `Laratrust <https://github.com/santigarcor/laratrust>`_.

**This documentation of TrustNoSql is currently under development. We work day and night to complete it as soon as possible.**

Key features
============

* permission/role/team
* cache support
* middleware with complex matching rules (SQL-like)
* custom model events
* convenient command-line interface

Why TrustNoSql?
===============

We decided to create TrustNoSql as we began to develop Laravel application without MySQL (or another relational) databases in favor for NoSQL systems, mainly MongoDB. At first we planned to adjust Laratrust to our needs, but as we kept on thinking, we felt that we need some more functionalities than Laratrust offers, like complex middleware rules.

Since we believe that TrustNoSql can prove useful for a wide range of Laravel developers, we wanted it to be an open-source project from the very beggining. As MongoDB fans, we must admit that it would be great if TrustNoSql helps to promote MongoDB among Laravel developers.

Requirements
============

In order to have TrustNoSql working correctly you need to have:

* `Laravel <https://packagist.org/packages/laravel/framework>`_ >=5.4
* `Laravel-MongoDB <https://packagist.org/packages/jenssegers/mongodb>`_ >=3.2
* PHP >=7.0

TrustNoSql would probably run also with Laravel 5.3 (and Laravel-MongoDB >=3.1), but no tests for Laravel 5.3 have been performed. As soon as we run such tests, we'll relase official statement on that.

Make sure your Laravel application have correct configuration for Laravel-MongoDB (database configuration).

Installation
############

Via Composer
============

You can install TrustNoSql with Composer:

.. code-block:: bash

    $ composer require vegvisir/trustnosql

Service provider and alias (Laravel <5.5)
=========================================

Add the TrustNoSql service provider in your ``config/app.php`` file:

.. code-block:: php

    <?php

    'providers' => [
        // ...
        Vegvisir\TrustNoSql\TrustNoSqlServiceProvider::class,
        // ...
    ]

Also, you should add an alias to your ``config/app.php`` file:

.. code-block:: php

    <?php

    'aliases' => [
        // ...
        'TrustNoSql' => Vegvisir\TrustNoSql\TrustNoSqlFacade::class,
        // ...
    ]

Create models for your TrustNoSql
=================================

Permission (obligatory)
-----------------------

In your models directory, create a base model for permission, using a TrustNoSql permission trait.

.. code-block:: php

    <?php

    namespace App\Models;

    use Vegvisir\TrustNoSql\Models\Permission as TrustNoSqlPermission;
    use Vegvisir\TrustNoSql\Traits\PermissionTrait as TrustNoSqlPermissionTrait;

    class Permission extends TrustNoSqlPermission
    {
        use TrustNoSqlPermissionTrait;
    }

``Vegvisir\TrustNoSql\Models\Permission`` extends Moloquent model. ``Vegvisir\TrustNoSql\Traits\PermissionTrait`` provides necessary methods.

Role
----

In your models directory, create a base model for role, using a TrustNoSql role trait.

.. code-block:: php

    <?php

    namespace App\Models;

    use Vegvisir\TrustNoSql\Models\Role as TrustNoSqlRole;
    use Vegvisir\TrustNoSql\Traits\RoleTrait as TrustNoSqlRoleTrait;

    class Role extends TrustNoSqlRole
    {
        use TrustNoSqlRoleTrait;
    }

``Vegvisir\TrustNoSql\Models\Role`` extends Moloquent model. ``Vegvisir\TrustNoSql\Traits\RoleTrait`` provides necessary methods.

User
----

Additionaly to permission and role models, you need to modify your user model, adding a TrustNoSql user trait:

.. code-block:: php

    <?php

    namespace App\Models;

    use Jenssegers\Mongodb\Auth\User as Authenticable;
    use Vegvisir\TrustNoSql\Traits\UserTrait as TrustNoSqlUserTrait;

    class User extends Authenticable
    {
        use TrustNoSqlUserTrait;
        // ...
    }

Optimally, you could also extend your user model with TrustNoSql user model. It extends ``Jenssegers\Mongodb\Auth\User``, so you don't need to worry about your user model methods:

.. code-block:: php

    <?php

    namespace App\Models;

    use Vegvisir\TrustNoSql\Models\User as Authenticable;
    use Vegvisir\TrustNoSql\Traits\UserTrait as TrustNoSqlUserTrait;

    class User extends Authenticable
    {
        use TrustNoSqlUserTrait;
        // ...
    }

Thus, your user model will inherit implementation of the ``UserInterface`` contract, being a part of TrustNoSql package, making your code more compatible with SOLID principles.

Team
----

**This model is optional - create it only if you intend to use team functionality.**

In your models directory, create a base model for team, using a TrustNoSql team trait.

.. code-block:: php

    <?php

    namespace App\Models;

    use Vegvisir\TrustNoSql\Models\Team as TrustNoSqlTeam;
    use Vegvisir\TrustNoSql\Traits\TeamTrait as TrustNoSqlTeamTrait;

    class Team extends TrustNoSqlTeam
    {
        use TrustNoSqlTeamTrait;
    }

``Vegvisir\TrustNoSql\Models\Team`` extends Moloquent model. ``Vegvisir\TrustNoSql\Traits\TeamTrait`` provides necessary methods.

Middleware setup
================

If you want to use middleware in your application, add folowing lines to your ``app/Http/Kernel.php`` file under ``$routeMiddleware``:

.. code-block:: php

    <?php

    protected $routeMiddleware = [
        // ...
        'permission' => \Vegvisir\TrustNoSql\Middleware\Permission::class,
        'role' => \Vegvisir\TrustNoSql\Middleware\Role::class,
        'reject' => \Vegvisir\TrustNoSql\Middleware\Reject::class,
        'trust' => \Vegvisir\TrustNoSql\Middleware\Trust::class,
        'team' => \Vegvisir\TrustNoSql\Middleware\Team::class,
        // ...
    ];

Of course, you can use only one of our middleware classes. You can also use your custom middleware aliases, f.e. ``wedontlikethem`` instead of ``reject`` or ``yescomeon`` instead of ``trust``, but remember to use your custom aliases while defining middleware routes.

Configuration
#############

All configuration setting are included in ``config/trustnosql.php`` file.

If you don't see ``trustnosql.php`` in ``config`` folder, try publishing config files from TrustNoSql:

.. code-block:: bash

    $ artisan vendor:publish

You'll see that the ``config/trustnosql.php`` file is divided into few sections, reflecting concepts and key parts of TrustNoSql:

* Cache
* Command-line interface (CLI)
* Collections
* Events
* Middleware
* Permissions
* Teams
* User models

Cache
=====

Use cache
^^^^^^^^^

Set ``use_cache`` to ``false`` if you don't want to use cache.

CLI
===

Use CLI
^^^^^^^

Set ``use_cli`` to ``false`` if you don't want TrustNoSql service provider to register artisan commands.

Signature
^^^^^^^^^

You can change the default ``trustnosql`` signature to any of your choice. Set ``signature`` to the signature of your choice.

Collections
===========

You can set collection names for roles, permissions, teams and users. If you use default names, omit that section.

Events
======

Settings for custom model events handling within TrustNoSql.

TrustNoSql offers custom model events functionality. You can specify your own event handlers for such events as attaching permissions/roles/teams to user or detaching them.

Use events
^^^^^^^^^^

Set ``use_events`` to ``false`` if you don't want to use custom model events within TrustNoSql at all.

Observers
^^^^^^^^^

If you want to specify your own event handlers, you need to create model observers and declare them in the ``observers`` section of the events config.

Supposing, you put your ``PermissionObserver``, ``RoleObserver``, ``TeamObserver`` and ``UserObserver`` under ``app\observers``, your configuration should look as following:

.. code-block:: php

    <?php

    return [
        // ...

        'events' => [

            // Whether to use events
            'use_events' => true,

            // Observers list
            'observers' => [

                // Observer for Permission model
                'Permission' => \App\PermissionObserver::class,

                // Observer for Role model
                'Role' => \App\RoleObserver::class,

                // Observer for Team model
                'Team' => \App\TeamObserver::class,

                // Observer for application user model
                'User' => \App\UserObserver::class,
            ],
        ],
    ];

You can override some of the observers, not all four.

Middleware
==========

Handling
^^^^^^^^

You can choose from two available handling methods, that TrustNoSql middleware will call whenever user is not authorized to access a resource: ``abort`` and ``redirect``.

``abort`` handler means that application will throw a 403 exception. ``redirect`` means the application will redirect user to other route - moreover, an error message can be flashed.


Usage
#####

Permission
==========

Permission is a right to perform a specific task. Permissions can be attached both to Roles and (explicitely) Users.

Permissions have three fillable fields:

* ``name`` - a unique name created in a manner described below,
* ``display_name`` - a user-friendly name of the permission,
* ``description`` - a user-friendly description of the permission.

Permission names
^^^^^^^^^^^^^^^^

Unlike it Lararust or Zizaco's Entrust packages, TrustNoSql permissions names (field ``name``) must be created in a ``namespace/task`` manner. The slash sign ``/`` is **really important**, because it's used by TrustNoSql to distinguish namespace from task.

Therefore such permission names are not valid:

* ``users-create``
* ``createusers``
* ``users:create``

The only valid permission name for creating users would be built that way:

* ``user/create``
* ``users/create``
* or similar

It's important not to use ``all`` or ``*`` as a task name, since those are keywords used by our wildcard permission functionality.

Checking for permissions
^^^^^^^^^^^^^^^^^^^^^^^^

Given that your user is stored within ``$user`` variable, and you want to check if ``$user`` can create users (permission ``user/create``), you can do that using one of following methods:

.. code-block:: php

    <?php

    $user->hasPermission('user/create');
    $user->hasPermissions('user/create');
    $user->can('user/create');

    // You can all TrustNoSql facade method...
    TrustNoSql::can($user, 'user/create');

    // ...or use our magic can methods
    $user->canCreateUser();
    TrustNoSql::canCreateUser($user);

As an argument for ``hasPermission``, ``hasPermissions`` and ``can`` methods, you can pass:

* permission name as a string (``user/create``),
* permission names as comma-separated string (``user/create,user/delete``),
* array of permission names (``['user/create', 'user/delete']``),
* a wildcard permission name (``user/*`` or ``user/all``).

Wildcard permissions
^^^^^^^^^^^^^^^^^^^^

TrustNoSql provides wildcard permission functionality to use for checking if a user can perform a specific task.

Currently, we offer support for ``*`` wildcard, with its alias ``all``, like in the example below:

.. code-block:: php

    <?php

    /*
     * Suppose the $user has the permissions: order/view and order/update
     */

    $user->can('order/view');         // true
    $user->can('order/*');            // true
    $user->can('order/all');          // true
    $user->can('order/everything');   // false
    $user->can('orders/*');           // false because of namespace mismatch

Role
====

Role is a set of permissions. Users can have roles, like ``admin`` or ``manager``, and that means they have a set of permissions related to the roles they have.

Roles have three fillable fields:

* ``name`` - a unique name created in a manner described below,
* ``display_name`` - a user-friendly name of the role,
* ``description`` - a user-friendly description of the role.

Role names
^^^^^^^^^^

Roles can have any name you think of (unlike permisisons). However, it's a good practice to keep your role names as representative as they can be.

Checking for roles
^^^^^^^^^^^^^^^^^^

Given that your user is stored within ``$user`` variable, and you want to check if ``$user`` has a role ``manager``, you can do that using one of following methods:

.. code-block:: php

    <?php

    $user->hasRole('manager');
    $user->hasRoles('manager');
    $user->isA('manager');
    $user->isAn('manager');

    // You can all TrustNoSql facade method...
    TrustNoSql::isA($user, 'manager');
    TrustNoSql::isAn($user, 'manager');

Similar to permissions, you can pass an array of role names as an argument to one of the abovementioned methods.

Team
====

Grabbable
=========

Events
======

Middleware
==========

Command-line interface (CLI)
############################

TrustNoSql comes with handy CLI of its own. It's especially useful at the initial phase of application development, when you have to create admin roles.

TrustNoSql CLI provides all possible actions for Permission, Role and Team (create, delete, attach, detach, list, info), as well as detailed info for chosen user.

Commands description
====================

You don't have to specify parameters for commands inline, as all of them provide simple interactive interface.

Permission namespace
--------------------

+----------------------------------+----------------------------------------------------+
| Command                          | Description                                        |
+==================================+====================================================+
| ``trustnosql:permission:attach`` | Attaches permission(s) to role(s) and/or user(s)   |
+----------------------------------+----------------------------------------------------+
| ``trustnosql:permission:create`` | Creates new permission(s)                          |
+----------------------------------+----------------------------------------------------+
| ``trustnosql:permission:delete`` | Deletes permission(s)                              |
+----------------------------------+----------------------------------------------------+
| ``trustnosql:permission:detach`` | Detaches permission(s) from role(s) and/or user(s) |
+----------------------------------+----------------------------------------------------+
| ``trustnosql:permission:info``   | Shows detailed information about permission        |
+----------------------------------+----------------------------------------------------+
| ``trustnosql:permissions``       | Shows a list of permissions                        |
+----------------------------------+----------------------------------------------------+

Role namespace
--------------

+----------------------------+----------------------------------------------+
| Command                    | Description                                  |
+============================+==============================================+
| ``trustnosql:role:attach`` | Attaches role(s) to team(s) and/or user(s)   |
+----------------------------+----------------------------------------------+
| ``trustnosql:role:create`` | Creates new role(s)                          |
+----------------------------+----------------------------------------------+
| ``trustnosql:role:delete`` | Deletes role(s)                              |
+----------------------------+----------------------------------------------+
| ``trustnosql:role:detach`` | Detaches role(s) from team(s) and/or user(s) |
+----------------------------+----------------------------------------------+
| ``trustnosql:role:info``   | Shows detailed information about role        |
+----------------------------+----------------------------------------------+
| ``trustnosql:roles``       | Shows a list of roles                        |
+----------------------------+----------------------------------------------+

Team namespace
--------------------

+----------------------------+----------------------------------------------+
| Command                    | Description                                  |
+============================+==============================================+
| ``trustnosql:team:attach`` | Attaches team(s) to role(s) and/or user(s)   |
+----------------------------+----------------------------------------------+
| ``trustnosql:team:create`` | Creates new team(s)                          |
+----------------------------+----------------------------------------------+
| ``trustnosql:team:delete`` | Deletes team(s)                              |
+----------------------------+----------------------------------------------+
| ``trustnosql:team:detach`` | Detaches role(s) from team(s) and/or team(s) |
+----------------------------+----------------------------------------------+
| ``trustnosql:team:info``   | Shows detailed information about team        |
+----------------------------+----------------------------------------------+
| ``trustnosql:teams``       | Shows a list of teams                        |
+----------------------------+----------------------------------------------+

User namespace
--------------------

+--------------------------+---------------------------------------+
| Command                  | Description                           |
+==========================+=======================================+
| ``trustnosql:user:info`` | Shows detailed information about user |
+--------------------------+---------------------------------------+

Troubleshooting
###############

So far, this section is empty. As soon as we get info on some troubles you might experience, we will definitely provide some solutions.

License
#######

TrustNoSql is an open-source project, distributed under the terms of GNU General Public License v3.0. More information, along with the license terms can be found at `https://www.gnu.org/licenses/gpl-3.0.en.html <https://www.gnu.org/licenses/gpl-3.0.en.html>`_.

Contributing
############

You are more than welcome to contribute to TrustNoSql. We look forward to your pull requests, hints and ideas, as well as constructive criticism of TrustNoSql.

Please report all issues at `https://github.com/vegvisir-for-all/trustnosql/issues <https://github.com/vegvisir-for-all/trustnosql/issues>`_. We also provide e-mail address vegvisir.for.all(at)gmail.com, but, unfortunately, we cannot guarantee swift responses (or reponses whatsoever) to your e-mail messages.

Tests
=====

TrustNoSql uses Travis for continuous integration and tests. However, if you want to perform tests of your own, you can use our ``docker-compose.yml`` and ``Dockerfile`` files provided at the root of the package. All you have to do is (supposing you have Docker and Docker Compose installed) run:

.. code-block:: bash

    $ docker-compose up

About us
########

TrustNoSql have been created by Vegvisir - a small software house from Warsaw, Poland and its main developer was Marek Ognicki (Kapusta).

We specialize in Laravel applications, using NoSQL databases (mainly MongoDB), deployed on mainly Google Cloud Engine. We tend to keep our code clean and professional (SOLID/DRY principles), that's why we use such services like StyleCI. For our tests we use TravisCI.

We'd love to make your software dreams come true. If you believe, we migt be the right choice for you, let us know :)
