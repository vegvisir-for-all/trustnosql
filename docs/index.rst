##########
TrustNoSql
##########

.. image:: https://readthedocs.org/projects/trustnosql/badge/?version=latest :target: https://trustnosql.readthedocs.io/en/latest/?badge=latest :alt: Documentation Status

What is TrustNoSql
##################

TrustNoSql is a Laravel (>=5.3) role/permission manager for MongoDB-based applications. It supports roles, permissions, and teams, provides a complex way of determining ownage (grabbing) of objects, and offers a nice CLI for your convenience. TrustNoSql is built atop a great `jenssegers/laravel-mongodb <https://github.com/jenssegers/laravel-mongodb>`_ package, and it's inspired by `Laratrust <https://github.com/santigarcor/laratrust>`_.

**TrustNoSql is in the development phase. We can take no responsibility for any damage it creates when used in production projects. However, we hope to publish a stable version soon.**

Requirements
############

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
