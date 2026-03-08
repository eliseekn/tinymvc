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
use Core\Database\Repository;
use Core\Support\Pagination;

class User extends Model
{
    use HasFactory;

    public function __construct()
    {
        parent::__construct('users');
    }

    public static function query(): Model
    {
        return new self;
    }

    public static function find(int $id): ?Model
    {
        return self::query()->findBy('id', $id);
    }

    public static function findByEmail(string $email): ?Model
    {
        return self::query()->findBy('email', $email);
    }

    public static function findAllByEmailVerifiedAt(string $column): array
    {
        return self::query()
            ->select('*')
            ->where('column', $column)
            ->getAll();
    }

    public static function findByIdentifier(string $value): ?Model
    {
        return self::query()->findBy(config('security.auth.identifier'), $value);
    }

    public static function findAllByRole(string $role): array
    {
        return self::query()
            ->select(['users.email', 'roles.name'])
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', $role)
            ->getAll();
    }

    public static function findAllPaginate(int $perPage, int $page, ?string $search = null): Pagination
    {
        $userId = auth()?->getId();

        return self::query()
            ->select('*')
            ->where('id', '<>', $userId)
            ->when(! is_null($search), function (Repository $r) use ($search) {
                $r->andRaw("(name LIKE '%$search%' OR email LIKE '%$search%')");
            })
            ->orderDesc('created_at')
            ->paginate($perPage, $page);
    }
}
