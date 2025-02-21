<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Database\Models;

use Core\Database\Factory\HasFactory;
use Core\Database\Model;

class Token extends Model
{
    use HasFactory;

    public function __construct()
    {
        parent::__construct('tokens');
    }

    public static function find(int $id): Model|false
    {
        return (new self)->findBy('id', $id);
    }

    public static function findByValue(string $value): Model|false
    {
        return (new self())->findBy('value', $value);
    }

    public static function findByUser(string $email): Model|false
    {
        return (new self())->findBy('email', $email);
    }

    public static function findByDescription(string $email, string $description): Model|false
    {
        return (new self())
            ->where('email', $email)
            ->and('description', $description)
            ->first();
    }

    public static function all(): array|false
    {
        return (new self)->getAll();
    }
}
