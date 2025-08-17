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

class Version20210403034739
{
    public function up(): void
    {
        Schema::createTable('tokens')
            ->addPrimaryKey()->notNull()
            ->addVarChar('identifier')->notNull()
            ->addVarChar('value')->notNull()->unique()
            ->addDateTime('expires_at')->null()
            ->addVarChar('description')->notNull()
            ->addDefaultTimestamps()
            ->run();
    }

    public function down(): void
    {
        Schema::dropTable('tokens');
    }
}
