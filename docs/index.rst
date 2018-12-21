##########
TrustNoSql
##########

.. image:: http://img.shields.io/github/release/vegvisir/trustnosql.svg :target: https://packagist.org/packages/vegvisir/trustnosql :alt: Latest Stable Version
.. image:: http://img.shields.io/packagist/dm/vegvisir/trustnosql.svg :target: https://packagist.org/packages/vegvisir/trustnosql :alt: Total Downloads
.. image:: https://api.travis-ci.org/vegvisir-for-all/trustnosql.svg?branch=master :target: https://travis-ci.org/vegvisir-for-all/trustnosql :alt: Build Status
.. image:: http://img.shields.io/coveralls/vegvisir-for-all/trustnosql.svg?branch=master :target: https://coveralls.io/r/vegvisir-for-all/trustnosql?branch=master :alt: Coverage Status
.. image:: https://github.styleci.io/repos/161784926/shield?branch=master :target: https://github.styleci.io/repos/161784926 :alt: StyleCI
.. image:: https://readthedocs.org/projects/trustnosql/badge/?version=latest :target: https://trustnosql.readthedocs.io/en/latest/?badge=latest :alt: Documentation Status

What is TrustNoSql
##################

TrustNoSql is a Laravel (>=5.3) role/permission manager for MongoDB-based applications. It supports roles, permissions, and teams, provides a complex way of determining ownage (grabbing) of objects, and offers a nice CLI for your convenience. TrustNoSql is built atop a great `jenssegers/laravel-mongodb <https://github.com/jenssegers/laravel-mongodb>`_ package, and it's inspired by `Laratrust <https://github.com/santigarcor/laratrust>`_.

**TrustNoSql is in the development phase. We can take no responsibility for any damage it creates when used in production projects. However, we hope to publish a stable version soon.**

**This documentation is also under development. We work day and night to complete it as soon as possible.**

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
