<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Models;

use Core\Database\Model;

class User extends Model
{
    public static $table = 'users';

    public function __construct()
    {
        parent::__construct(static::$table);
    }
}
