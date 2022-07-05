<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Models;

use Core\Database\Concerns\HasFactory;
use Core\Database\Model;

class Token extends Model
{
    use HasFactory;

    public static string $table = 'tokens';

    public function __construct()
    {
        parent::__construct(static::$table);
    }
}
