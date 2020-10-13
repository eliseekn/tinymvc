<?php

namespace App\Database\Models;

use Framework\ORM\Model;

class PasswordResetModel extends Model
{    
    /**
     * name of table
     *
     * @var string
     */
    protected static $table = 'password_reset';
}
