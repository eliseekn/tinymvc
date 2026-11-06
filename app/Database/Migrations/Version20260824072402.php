<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Migrations;

use Core\Database\Schema;

final class Version20260824072402
{
    public function up(): void
    {
        Schema::createTable('admins')
            ->addPrimaryKey()->notNull()
            ->addVarChar('username')->notNull()
            ->addVarChar('email')->notNull()
            ->addVarChar('password')->notNull()
            ->addVarChar('avatar')->null()
            ->addDefaultTimestamps()
            ->run();
    }

    public function down(): void
    {
        Schema::dropTable('admins');
    }
}
