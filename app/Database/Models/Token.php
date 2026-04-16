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

class Token extends Model
{
    use HasFactory;

    public function __construct(public array $attributes = [])
    {
        parent::__construct('tokens', $attributes);
    }

    public static function query(): Model
    {
        return new self;
    }

    public static function find(int $id): ?Model
    {
        return self::query()->findBy('id', $id);
    }

    public static function findByValue(string $value): ?Model
    {
        return self::query()->findBy('value', $value);
    }

    public static function findByDescription(string $identifier, string $description): ?Model
    {
        return self::query()
            ->select('*')
            ->where('identifier', $identifier)
            ->and('description', $description)
            ->first();
    }
}
