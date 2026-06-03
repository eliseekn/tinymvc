<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\User;

use App\Policies\ProfilePolicy;
use Core\Database\Model;
use Core\Support\Alert;

final class DeleteAvatarUseCase
{
    public function handle(Model $user): void
    {
        $user->authorize(new ProfilePolicy)->onDelete();

        if (! storage(config('storage.uploads'))->deleteFile($user->get('avatar'))) {
            Alert::toast('Failed to delete avatar')->error();
            response()->back()->send();
        }

        if (! $user->set(['avatar' => null])->save()) {
            Alert::toast('Failed to update profile')->error();
            response()->back()->send();
        }

        session()->create('user', $user->get());
        Alert::toast('Profile updated')->success();

        response()->back()->send();
    }
}
