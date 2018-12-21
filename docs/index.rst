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

Features
========

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

Middleware setup
================

If you want to use middleware in your application, add folowing lines to your ``app/Http/Kernel.php`` file under `$routeMiddleware`:

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

.. code-block:: php

Publish config
==============

Create models for your TrustNoSql
=================================

Usage
#####

Configuration
=============

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

Concepts
========

Permission
----------

Role
----

Team
----

Grabbable
---------

Events
------

Middleware
----------

Command-line interface (CLI)
############################

TrustNoSql comes with handy CLI of its own. It's especially useful at the initial phase of application development, when you have to create admin roles.

TrustNoSql CLI provides all possible actions for Permission, Role and Team (create, delete, attach, detach, list, info), as well as detailed info for chosen user.

Commands description
====================

You don't have to specify parameters for commands inline, as all of them provide simple interactive interface.

Permission namespace
--------------------

+----------------------------------+--------------------------------------------------+
| Command                          | Description                                      |
+==================================+==================================================+
| ``trustnosql:permission:attach`` | Attaches permission(s) to role(s) and/or user(s) |
+----------------------------------+--------------------------------------------------+

Troubleshooting
###############

License
#######

Contributing
############

About us
########
