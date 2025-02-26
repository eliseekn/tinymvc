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

    public static function all(): array|false
    {
        return (new self)->getAll('*');
    }

    public static function allPaginate($perPage, $page, ?string $search = null): Pagination
    {
        $userId = auth()->get('id');

        return (new self)
            ->select('*')
            ->where('id', '<>', $userId)
            ->subQueryWhen(! is_null($search), function ($q) use ($search) {
                $q->andRaw("(name LIKE '%$search%' OR email LIKE '%$search%')");
            })
            ->orderDesc('created_at')
            ->paginate((int) $perPage, (int) $page);
    }
}
