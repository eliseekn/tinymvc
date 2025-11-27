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

final class Version20210403034738
{
    public function up(): void
    {
        Schema::createTable('users')
            ->addPrimaryKey()->notNull()
            ->addVarchar('name')->notNull()
            ->addVarchar('email')->notNull()->unique()
            ->addVarchar('password')->notNull()
            ->addDateTime('email_verified_at')->null()
            ->addVarchar('role')
            ->addDefaultTimestamps()
            ->run();
    }

    public function down(): void
    {
        Schema::dropTable('users');
    }
}
