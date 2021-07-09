<?php

namespace App\Database\Migrations;

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
            ->addBoolean('verified')->default(0)
            ->addString('password')
            ->migrate();
    }
    
    public function drop()
    {
        Migration::dropTable($this->table);
    }
}
