<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Api\v1\User;

use App\Database\Entities\User;
use App\Policies\ProfilePolicy;
use Core\Support\Logger;

final class DeleteAvatarUseCase
{
    public function handle(User $user): bool
    {
        $user->toModel()->isAuthorized(new ProfilePolicy)->onDelete();

        if (! storage(config('storage.uploads'))->deleteFile($user->getAvatar())) {
            Logger::error('Failed to delete avatar');
        }

        $user->setAvatar(null);

        return (bool) $user->toModel()->save();
    }
}
