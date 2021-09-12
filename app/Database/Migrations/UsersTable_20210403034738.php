<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Migrations;

use App\Database\Models\User;
use Core\Database\Migration;

class UsersTable_20210403034738
{         
    protected $table = 'users';

    public function create()
    {
        Migration::createTable($this->table)
            ->addPrimaryKey('id')
            ->addString('name')
            ->addString('email')->unique()
            ->addString('password')
            ->addBoolean('verified')->default(true)
            ->addString('role')->default(User::ROLE_USER)
            ->migrate();
    }
    
    public function drop()
    {
        Migration::dropTable($this->table);
    }
}
