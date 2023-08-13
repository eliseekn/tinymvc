<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Models;

use Core\Database\Model;

class Token extends Model
{
    public function __construct()
    {
        parent::__construct('tokens');
    }

    public static function findByValue(string $value): Model|false
    {
        return (new self())->findBy('value', $value);
    }

    public static function exists(string $email, string $description): Model|false
    {
        $token = (new self())
            ->where('email', $email)
            ->and('description', $description);

        return !$token->exists() ? false : $token->first();
    }

    public static function findLatest(string $email, string $description): Model|false
    {
        return (new self())
            ->where('email', $email)
            ->and('description', $description)
            ->newest()
            ->first();
    }
}
