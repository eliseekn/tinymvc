<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Models;

use App\Database\Entities\Role;
use Core\Database\Factory\HasFactory;
use Core\Database\Model;
use Core\Support\Pagination;

class RoleModel extends Model
{
    use HasFactory;

    protected static function defaultTable(): string
    {
        return 'roles';
    }

    public static function query(): self
    {
        return new self;
    }

    public static function find(int $id): ?Role
    {
        return self::query()->findBy('id', $id)?->toEntity(Role::class);
    }

    public static function findByName(string $name): ?Role
    {
        return self::query()->findBy('name', $name)?->toEntity(Role::class);
    }

    public static function findAll(): array
    {
        $roles = self::query()->select('*')->getAll();

        return array_map(fn (self $role) => $role->toEntity(Role::class), $roles);
    }

    public static function findAllPaginate(): Pagination
    {
        $perPage = (int) request()->queries()->get('per_page', 10);
        $page = (int) request()->queries()->get('page', 1);

        $pagination = self::query()
            ->select('*')
            ->paginate($perPage, $page);

        return $pagination->setItems(
            array_map(fn (self $role) => $role->toEntity(Role::class), $pagination->getItems())
        );
    }
}
