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
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;

final class DeleteAvatarUseCase
{
    public function handle(Model $user): void
    {
        $user->authorize(new ProfilePolicy)->onDelete();

        if (! storage(config('storage.uploads'))->deleteFile($user->get('avatar'))) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => 'Failed to delete avatar',
            ])->send(HttpCode::INTERNAL_SERVER_ERROR);
        }

        if (! $user->set(['avatar' => null])->save()) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => 'Failed to delete profile',
            ])->send(HttpCode::INTERNAL_SERVER_ERROR);

        }

        response()->json([
            'status' => ResponseStatus::SUCCESS,
            'message' => 'Profile updated',
        ])->send(HttpCode::OK);
    }
}
