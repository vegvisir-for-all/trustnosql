<?php

/*
 * This file is part of the TrustNoSql package.
 * TrustNoSql provides comprehensive role/permission/team functionality
 * for Laravel applications using MongoDB database.
 *
 * @copyright 2018-19 Vegvisir Sp. z o.o. <vegvisir.for.all@gmail.com>
 * @license GNU General Public License, version 3
 */

function test_seed_lessons()
{
    return [
        [
            'name' => 'Cats: introduction',
        ],
        [
            'name' => 'Cats: breeds',
        ],
        [
            'name' => 'Dogs: introduction',
        ],
        [
            'name' => 'Squirrels: introduction',
        ],
        [
            'name' => 'Squirrels: subspecies',
        ],
        [
            'name' => 'Elephant: introduction',
        ],
        [
            'name' => 'Domestic: introduction',
        ],
        [
            'name' => 'Domestic: species',
        ],
    ];
}
