<?php

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
            ->addBoolean('verified')->default(0)
            ->addString('role')->default(User::ROLE_USER)
            ->migrate();
    }
    
    public function drop()
    {
        Migration::dropTable($this->table);
    }
}
