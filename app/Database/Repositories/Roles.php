<?php

namespace App\Database\Repositories;

use Framework\Database\Repository;

class Roles extends Repository
{    
    /**
     * name of table
     *
     * @var string
     */
    public $table = 'roles';

    /**
     * roles constants
     * 
     * @var array
     */
    public const ROLE = [
        0 => 'admin',
        1 => 'customer',
        2 => 'user',
        3 => 'visitor'
    ];
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct($this->table);
    }
}
