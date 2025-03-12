<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

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

    public static function find(int $id): ?Model
    {
        return (new self)->findBy('id', $id);
    }

    public static function findByValue(string $value): ?Model
    {
        return (new self)->findBy('value', $value);
    }

    public static function findByDescription(string $email, string $description): ?Model
    {
        return (new self)
            ->select('*')
            ->where('email', $email)
            ->and('description', $description)
            ->first();
    }

    public static function all(): array
    {
        return (new self)->getAll('*');
    }
}
