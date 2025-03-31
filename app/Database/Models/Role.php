<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Models;

use Core\Database\Factory\HasFactory;
use Core\Database\Model;

class Role extends Model
{
    use HasFactory;

    public function __construct()
    {
        parent::__construct('roles');
    }

    public static function findByName(string $name): ?Model
    {
        return (new self)->findBy('name', $name);
    }

    public static function findAll(): array
    {
        return (new self)
            ->select('*')
            ->getAll();
    }

    public static function findAllByName(): array
    {
        return (new self)
            ->select('name')
            ->getAll();
    }
}
