<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Database\Models;

use App\Database\Entities\Admin;
use App\Database\Factories\AdminFactory;
use Core\Database\Attributes\UseFactory;
use Core\Database\Attributes\UseTable;
use Core\Database\Factory\HasFactory;
use Core\Database\Model;
use Core\Notification\Notifiable;
use Core\Support\Pagination;

#[UseTable('admins')]
#[UseFactory(AdminFactory::class)]
class AdminModel extends Model
{
    use HasFactory;
    use Notifiable;

    public static function query(): self
    {
        return new self;
    }

    public static function find(int $id): ?Admin
    {
        return self::query()->findBy('id', $id)?->toEntity(Admin::class);
    }

    public static function findByUsername(string $username): ?Admin
    {
        return self::query()->findBy('username', $username)?->toEntity(Admin::class);
    }

    public static function findByEmail(string $email): ?Admin
    {
        return self::query()->findBy('email', $email)?->toEntity(Admin::class);
    }

    public static function findByIdentifier(string $value): ?Admin
    {
        return self::query()->findBy(config('security.auth.providers.admin.identifier'), $value)?->toEntity(Admin::class);
    }

    public static function findAll(): array
    {
        $data = self::query()->getAll('*');

        return array_map(fn (self $datum) => $datum->toEntity(Admin::class), $data);
    }

    public static function findAllPaginate(): Pagination
    {
        $perPage = (int) request()->queries()->get('per_page', 10);
        $page = (int) request()->queries()->get('page', 1);

        $pagination = self::query()
            ->select('*')
            ->paginate($perPage, $page);

        return $pagination->setItems(
            array_map(fn (self $datum) => $datum->toEntity(Admin::class), $pagination->getItems())
        );
    }
}
