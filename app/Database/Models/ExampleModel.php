<?php

namespace App\Database\Models;

use Framework\ORM\Model;

/**
 * ExampleModel
 * 
 * Example model class
 */
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
    
    /**
     * get row
     *
     * @param  string $email
     * @return mixed
     */
    public function get(string $email)
    {
        return $this->findSingle('email', '=', $email);
    }
}