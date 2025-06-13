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

class Version20250313135908
{
    public function up(): void
    {
        Schema::alterTable('users')
            ->deleteColumn('role')
            ->run();

        Schema::alterTable('users')
            ->addColumn()
            ->addBigInt('role_id')->null()->after('email_verified_at')
            ->run();

        Schema::alterTable('users')
            ->addConstraintForeignKey('users_role', 'role_id')->references('roles', 'id')
            ->run();
    }
}
