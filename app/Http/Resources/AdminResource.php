<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Resources;

use App\Database\Entities\Admin;
use Core\Database\Model;
use Core\Http\Resource;

class AdminResource extends Resource
{
    public function toArray(Model $model): array
    {
        $admin = $model->toEntity(Admin::class);

        return [
            'id' => $admin->getId(),
            'username' => $admin->getUsername(),
            'email' => $admin->getEmail(),
            'avatar' => is_null($admin->getAvatar()) ? null : storage_url('uploads/'.$admin->getAvatar()),
            'created_at' => $admin->getCreatedAt()?->locale(config('app.lang'))->isoFormat('Do MMM YYYY'),
            'updated_at' => $admin->getUpdatedAt()?->locale(config('app.lang'))->isoFormat('Do MMM YYYY'),
        ];
    }
}
