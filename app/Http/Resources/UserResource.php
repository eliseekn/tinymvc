<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Resources;

use App\Database\Entities\User;
use Core\Database\Model;
use Core\Http\Resource;

class UserResource extends Resource
{
    public function toArray(Model $model): array
    {
        $user = $model->toEntity(User::class);

        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            'avatar' => is_null($user->getAvatar()) ? null : storage_url('uploads/'.$user->getAvatar()),
            'role' => $user->role()?->getName(),
            'created_at' => $user->getCreatedAt()?->locale(config('app.lang'))->isoFormat('Do MMM YYYY'),
            'updated_at' => $user->getUpdatedAt()?->locale(config('app.lang'))->isoFormat('Do MMM YYYY'),
        ];
    }
}
