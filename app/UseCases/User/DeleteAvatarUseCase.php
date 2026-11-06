<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\User;

use App\Database\Entities\User;
use App\Exceptions\InternalServerException;
use App\Policies\ProfilePolicy;
use Core\Http\Auth\Auth;

final class DeleteAvatarUseCase
{
    public function handle(User $user): void
    {
        $user->toModel()->isAuthorized(new ProfilePolicy)->onDelete();

        if (! storage(config('storage.uploads'))->deleteFile($user->getAvatar())) {
            throw new InternalServerException('Failed to delete avatar');
        }

        $user->setAvatar(null);

        if (! (bool) $user->toModel()->save()) {
            throw new InternalServerException('Failed to update profile');
        }

        Auth::setSession($user);
    }
}
