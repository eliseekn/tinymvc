<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Migrations;

use Core\Database\Migration;

class UsersTable_20250220101052
{
    public function create(): void
    {
        Migration::alterTable('users')
            ->addColumn()
            ->addString('avatar')->nullable()
            ->run(true);
    }

    public function drop(): void
    {
        //
    }
}
