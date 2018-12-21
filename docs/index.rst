##########
TrustNoSql
##########

# .. image:: http://img.shields.io/github/release/vegvisir/trustnosql.svg :target: https://packagist.org/packages/vegvisir/trustnosql :alt: Latest Stable Version .. image:: http://img.shields.io/packagist/dm/vegvisir/trustnosql.svg :target: https://packagist.org/packages/vegvisir/trustnosql :alt: Total Downloads .. image:: https://api.travis-ci.org/vegvisir-for-all/trustnosql.svg?branch=master :target: https://travis-ci.org/vegvisir-for-all/trustnosql :alt: Build Status .. image:: http://img.shields.io/coveralls/vegvisir-for-all/trustnosql.svg?branch=master :target: https://coveralls.io/r/vegvisir-for-all/trustnosql?branch=master :alt: Coverage Status .. image:: https://github.styleci.io/repos/161784926/shield?branch=master :target: https://github.styleci.io/repos/161784926 :alt: StyleCI .. image:: https://readthedocs.org/projects/trustnosql/badge/?version=latest :target: https://trustnosql.readthedocs.io/en/latest/?badge=latest :alt: Documentation Status

Introduction
############

What is TrustNoSql
==================

TrustNoSql is a Laravel (>=5.3) role/permission manager for MongoDB-based applications. It supports roles, permissions, and teams, provides a complex way of determining ownage (grabbing) of objects, and offers a nice CLI for your convenience. TrustNoSql is built atop a great `jenssegers/laravel-mongodb <https://github.com/jenssegers/laravel-mongodb>`_ package, and it's inspired by `Laratrust <https://github.com/santigarcor/laratrust>`_.

**TrustNoSql is in the development phase. We can take no responsibility for any damage it creates when used in production projects. However, we hope to publish a stable version soon.**

**This documentation is also under development. We work day and night to complete it as soon as possible.**

Key features
============

* permission/role/team
* grabbable (ownable) objects support with two ways to determining ownership
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

* `Laravel <https://packagist.org/packages/laravel/framework>`_ >=5.3
* `Laravel-MongoDB <https://packagist.org/packages/jenssegers/mongodb>`_ >3.4
* PHP >7.0

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

    'providers' => [
        // ...
        Vegvisir\TrustNoSql\TrustNoSqlServiceProvider::class,
        // ...
    ]

Also, you should add an alias to your ``config/app.php`` file:

.. code-block:: php

    'aliases' => [
        // ...
        'TrustNoSql' => Vegvisir\TrustNoSql\TrustNoSqlFacade::class,
        // ...
    ]

Create models for your TrustNoSql
=================================

Permission
----------

Role
----

Team
----

Grabbable
---------

Middleware setup
================

If you want to use middleware in your application, add folowing lines to your ``app/Http/Kernel.php`` file under ``$routeMiddleware``:

.. code-block:: php

    protected $routeMiddleware = [
        // ...
        'ability' => \Vegvisir\TrustNoSql\Middleware\Ability::class,
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

Usage
#####

Permission
==========

Permission is a right to perform a specific task. Permissions can be attached both to Roles and (explicitely) Users.

Permission names
^^^^^^^^^^^^^^^^

Unlike it Lararust or Zizaco's Entrust packages, TrustNoSql permissions names must be created in a ``namespace/task`` manner. The slash sign ``/`` is **really important**, because it's used by TrustNoSql to distinguish namespace from task.

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

Checking for roles
^^^^^^^^^^^^^^^^^^

Given that your user is stored within ``$user`` variable, and you want to check if ``$user`` has a role ``manager``, you can do that using one of following methods:

.. code-block:: php

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
