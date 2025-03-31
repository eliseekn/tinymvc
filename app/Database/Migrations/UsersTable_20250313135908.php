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

class UsersTable_20250313135908
{
    public function up(): void
    {
        Migration::alterTable('users')
            ->deleteColumn('role')
            ->run();

        Migration::alterTable('users')
            ->addColumn()
            ->addBigInt('role_id')->nullable()
            ->run();

        Migration::alterTable('users')
            ->addForeignKey('role_id', 'users_role')->references('roles', 'id')
            ->run();
    }
}
