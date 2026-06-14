<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Api\v1\User;

use App\Policies\ProfilePolicy;
use Core\Database\Model;
use Core\Support\Logger;

final class DeleteAvatarUseCase
{
    public function handle(Model $user): bool
    {
        $user->isAuthorized(new ProfilePolicy)->onDelete();

        if (! storage(config('storage.uploads'))->deleteFile($user->get('avatar'))) {
            Logger::error('Failed to delete avatar');
        }

        return $user->set(['avatar' => null])->save();
    }
}
