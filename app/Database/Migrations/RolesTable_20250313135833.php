<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Migrations;

use Core\Database\Schema;

class RolesTable_20250313135833
{
    public function up(): void
    {
        Schema::createTable('roles')
            ->addPrimaryKey()->notNull()
            ->addString('name')->notNull()->unique()
            ->run();
    }

    public function down(): void
    {
        Schema::disableForeignKeyCheck();
        Schema::dropTable('roles');
        Schema::enableForeignKeyCheck();
    }
}
