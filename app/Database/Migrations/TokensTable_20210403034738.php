<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Migrations;

use App\Enums\TokenDescription;
use Core\Database\Migration;

class TokensTable_20210403034738
{         
    public function create(): void
    {
        Migration::createTable('tokens')
            ->addPrimaryKey()
            ->addString('email')
            ->addString('value')->unique()
            ->addDateTime('expire_at')->nullable()
            ->addString('description')->default(TokenDescription::PASSWORD_RESET_TOKEN->value)
            ->addTimestamps()
            ->run();
    }
    
    public function drop(): void
    {
        Migration::dropTable('tokens');
    }
}
