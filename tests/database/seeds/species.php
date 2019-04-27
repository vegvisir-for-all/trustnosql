<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

function test_seed_species()
{
    return [
        [
            'name' => 'Cat',
        ],
        [
            'name' => 'Dog',
        ],
        [
            'name' => 'Squirrel',
        ],
        [
            'name' => 'Elephant',
        ],
    ];
}
