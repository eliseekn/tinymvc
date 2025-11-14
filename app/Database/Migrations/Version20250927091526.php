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

class Version20250927091526
{
    public function up(): void
    {
        Schema::createTable('tasks')
            ->addPrimaryKey()->notNull()
            ->addVarChar('_key')->notNull()
            ->addVarChar('class')->notNull()
            ->addVarChar('execution_time')->notNull()
            ->addVarChar('status')->notNull()
            ->addInt('last_run')->null()
            ->addInt('retries')->notNull()->default(0)
            ->addDefaultTimestamps()
            ->run();
    }

    public function down(): void
    {
        Schema::dropTable('tasks');
    }
}
