<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\User;

use App\Exceptions\InternalServerException;
use App\Policies\ProfilePolicy;
use Core\Database\Model;

final class DeleteAvatarUseCase
{
    public function handle(Model $user): void
    {
        $user->isAuthorized(new ProfilePolicy)->onDelete();

        if (! storage(config('storage.uploads'))->deleteFile($user->get('avatar'))) {
            throw new InternalServerException('Failed to delete avatar');
        }

        if (! $user->set(['avatar' => null])->save()) {
            throw new InternalServerException('Failed to update profile');
        }

        session()->create('user', $user->get());
    }
}
