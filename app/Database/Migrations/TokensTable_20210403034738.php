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

class TokensTable_20210403034738
{
    public function up(): void
    {
        Migration::createTable('tokens')
            ->addPrimaryKey()
            ->addString('email')
            ->addString('value')->unique()
            ->addDateTime('expires_at')->nullable()
            ->addString('description')
            ->run();
    }

    public function down(): void
    {
        Migration::dropTable('tokens');
    }
}
