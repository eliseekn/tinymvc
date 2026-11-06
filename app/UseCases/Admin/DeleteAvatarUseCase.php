<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Admin;

use App\Database\Entities\Admin;
use App\Exceptions\InternalServerException;
use Core\Http\Auth\Auth;

final class DeleteAvatarUseCase
{
    public function handle(Admin $admin): void
    {
        if (! storage(config('storage.uploads'))->deleteFile($admin->getAvatar())) {
            throw new InternalServerException('Failed to delete avatar');
        }

        $admin->setAvatar(null);

        if (! (bool) $admin->toModel()->save()) {
            throw new InternalServerException('Failed to update profile');
        }

        Auth::setSession($admin);
    }
}
