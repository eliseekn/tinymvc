<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Models;

use Core\Database\Factory\HasFactory;
use Core\Database\Model;

class User extends Model
{
    use HasFactory;

    public function __construct()
    {
        parent::__construct('users');
    }

    public static function findByEmail(string $email): Model|false
    {
        return (new self())->findBy('email', $email);
    }

    public static function findAllWhereEmailLike(string $email): array|false
    {
        return (new self())->where('email', 'like', $email)->getAll();
    }
}
