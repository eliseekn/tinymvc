<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Models;

use Core\Database\Model;

class Token extends Model
{
    public static $table = 'tokens';

    public function __construct()
    {
        parent::__construct(static::$table);
    }
}
