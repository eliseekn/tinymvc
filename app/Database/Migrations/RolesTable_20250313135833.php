<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Migrations;

use Core\Database\Migration;

class RolesTable_20250313135833
{
    public function create(): void
    {
        Migration::createTable('roles')
            ->addPrimaryKey()
            ->addString('name')->unique()
            ->run();
    }

    public function drop(): void
    {
        Migration::disableForeignKeyCheck();
        Migration::dropTable('roles');
        Migration::enableForeignKeyCheck();
    }
}
