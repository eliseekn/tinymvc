<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Models;

use App\Enums\UserRole;
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

    public static function find(int $id): Model|false
    {
        return (new self)->findBy('id', $id);
    }

    public static function findByEmail(string $email): Model|false
    {
        return (new self)->findBy('email', $email);
    }

    public static function findByIdentifier(string $value): Model|false
    {
        return (new self)->findBy(config('security.auth.identifier'), $value);
    }

    public static function admins(): array|false
    {
        return (new self)
            ->select('email')
            ->where('role', UserRole::ADMIN->value)
            ->getAll();
    }

    public static function allPaginate($perPage, $page, ?string $search = null): Pagination
    {
        $userId = auth()->get('id');

        return (new self)
            ->select('*')
            ->whereNotEquals('id', $userId)
            ->subQueryWhen(! is_null($search), function (Repository $q) use ($search) {
                $q->andRaw("(name LIKE '%$search%' OR email LIKE '%$search%')");
            })
            ->orderDesc('created_at')
            ->paginate((int) $perPage, (int) $page);
    }
}
