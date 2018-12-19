TrustNoSql
==========

TrustNoSql is a Laravel role/permission manager for MongoDB-based applications. It supports roles, permissions, and teams, provides a complex way of determining ownage of objects, and offers a nice CLI for your convenience.
TrustNoSql is built atop a great jenssegers/mongodb package, and it's inspired by Laratrust. 

Table of contents
-----------------
* [Installation](#installation)
* [Configuration](#configuration)
* [Setup](#setup)


Installation
------------

You need to have jenssegers/mongodb package installed and configured before installing TrustNoSql. Since TrustNoSql has been tested to work with jenssegers/mongodb 3.2.x, it's strongly recommended to have a Laravel framework installed at version >5.3.

### 1. Install using composer

You can install TrustNoSql using `composer`:

```bash
$ composer require vegvisir/trustnosql
```

### 2. Add service provider and facade alias

If you're using Laravel before version 5.5, add the service provider in `config/app.php':

```php
'providers' => [
    ...,
    Vegvisir\TrustNoSql\TrustNoSqlServiceProvider::class,
    ...,
]
```

And a facade alias under `aliases` in `config/app.php`:

```php
'aliases' => [
    ...,
    'TrustNoSql' => Vegvisir\TrustNoSql\TrustNoSqlFacade.php,
    ...,
]
```

Configuration
-------------

### Publish config file

Setup
-----

### 1. Create models for Permission and Role

In your models directory (let's suppose, it's `App\Models`), create following files:

`App\Models\Permission.php:`
```php
<?php

namespace App\Models;

use Vegvisir\TrustNoSql\Models\Permission as PermissionModel;

class Permission extends PermissionModel {}
```

`App\Models\Role.php:`
```php
<?php

namespace App\Models;

use Vegvisir\TrustNoSql\Models\Role as RoleModel;

class Role extends RoleModel {}
```

### 2. Create model for Team

If you're planning to use team functionality, create Team model as well:

`App\Models\Team.php:`
```php
<?php

namespace App\Models;

use Vegvisir\TrustNoSql\Models\Team as TeamModel;

class Team extends TeamModel {}
```

### 3. Add traits for your User model

In your user model, add `UserTrait`:

```php
<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Jenssegers\Mongodb\Auth\User as Authenticatable;
// ...
use Vegvisir\TrustNoSql\Traits\UserTrait as TrustNoSqlUserTrait;


class User extends Authenticatable
{
    use HasApiTokens, Notifiable, TrustNoSqlUserTrait;

    // ...
}
```