<?php

namespace App\Database\Migrations;

use Core\Database\Migration;

class TokensTable_20210403034738
{         
    protected $table = 'tokens';

    public function create()
    {
        Migration::createTable($this->table)
            ->addPrimaryKey('id')
            ->addString('email', 30)
            ->addString('token', 50)->unique()
            ->addTimestamp('expire')->nullable()
            ->migrate();
    }
    
    public function drop()
    {
        Migration::dropTable($this->table);
    }
}
