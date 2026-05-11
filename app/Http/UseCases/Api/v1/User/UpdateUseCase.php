<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\Api\v1\User;

use App\Http\Resources\UserResource;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;

final class UpdateUseCase
{
    public function handle(array $data): void
    {
        $user = auth();

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        if (! $user->set($data)->save()) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => 'Failed to update user',
            ])->send(HttpCode::INTERNAL_SERVER_ERROR);
        }

        response()->json([
            'status' => ResponseStatus::SUCCESS,
            'message' => 'User updated',
            'data' => new UserResource($user)->handle(),
        ])->send(HttpCode::OK);
    }
}
