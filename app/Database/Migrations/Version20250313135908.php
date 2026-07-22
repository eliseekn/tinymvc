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

final class Version20250313135908
{
    public function up(): void
    {
        Schema::alterTable('users')
            ->dropColumn('role')
            ->run();
    }
}
