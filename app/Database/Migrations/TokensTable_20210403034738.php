<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Migrations;

use Core\Database\Migration;

class TokensTable_20210403034738
{         
    public function create()
    {
        Migration::createTable('tokens')
            ->addPrimaryKey('id')
            ->addString('email')
            ->addString('token')->unique()
            ->addTimestamp('expire')->nullable()
            ->addCurrentTimestamp()
            ->migrate();
    }
    
    public function drop()
    {
        Migration::dropTable('tokens');
    }
}
