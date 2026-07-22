<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Models;

use App\Database\Entities\User;
use Core\Database\Factory\HasFactory;
use Core\Database\Model;
use Core\Database\Repository;
use Core\Notification\Notifiable;
use Core\Support\Pagination;

class UserModel extends Model
{
    use HasFactory, Notifiable;

    protected static function defaultTable(): string
    {
        return 'users';
    }

    public static function query(): self
    {
        return new self;
    }

    public static function find(int $id): ?User
    {
        return self::query()->findBy('id', $id)?->toEntity(User::class);
    }

    public static function findByEmail(string $email): ?User
    {
        return self::query()->findBy('email', $email)?->toEntity(User::class);
    }

    public static function findByIdentifier(string $value): ?User
    {
        return self::query()->findBy(config('security.auth.identifier'), $value)?->toEntity(User::class);
    }

    public static function findAllByRole(string $role): array
    {
        $users = self::query()
            ->select('users.*')
            ->join('roles', 'users.role_id', '=', 'roles.id')
            ->where('roles.name', $role)
            ->getAll();

        return array_map(fn (self $user) => $user->toEntity(User::class), $users);
    }

    public static function findAllPaginate(): Pagination
    {
        $userId = auth()?->getId();
        $perPage = (int) request()->queries()->get('per_page', 10);
        $page = (int) request()->queries()->get('page', 1);
        $search = request()->queries()->get('search');

        $pagination = self::query()
            ->select('*')
            ->where('users.id', '<>', $userId)
            ->when(! is_null($search), function (Repository $r) use ($search) {
                $r->andRaw('(users.name LIKE ? OR users.email LIKE ?)', ["%$search%", "%$search%"]);
            })
            ->orderDesc('users.created_at')
            ->paginate($perPage, $page);

        return $pagination->setItems(
            array_map(fn (self $user) => $user->toEntity(User::class), $pagination->getItems())
        );
    }
}
