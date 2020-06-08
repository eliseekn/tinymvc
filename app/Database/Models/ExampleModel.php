<?php

namespace App\Database\Models;

use Framework\ORM\Model;

class ExampleModel extends Model
{    
    /**
     * name of table
     *
     * @var string
     */
    protected $table = 'name_of_table';

    /**
     * instantiates class
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct($this->table);
    }
}