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

    public function __construct(public array $attributes = [])
    {
        parent::__construct('roles', $attributes);
    }

    public static function query(): Model
    {
        return new self;
    }

    public static function findByName(string $name): ?Model
    {
        return self::query()->findBy('name', $name);
    }

    public static function findAll(): array
    {
        return self::query()
            ->select('*')
            ->getAll();
    }

    public static function findAllByName(): array
    {
        return self::query()
            ->select('name')
            ->getAll();
    }
}
