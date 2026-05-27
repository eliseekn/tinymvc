<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\Api\v1\User;

use App\Database\Models\User;
use App\Http\Resources\UserResource;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;

final class StoreUseCase
{
    public function handle(array $data): void
    {
        $data['password'] = bcrypt($data['password']);
        $user = User::factory()->create($data);

        if (! $user) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => 'Failed to create user',
            ])->send(HttpCode::INTERNAL_SERVER_ERROR);
        }

        response()->json([
            'status' => ResponseStatus::SUCCESS,
            'message' => 'User created',
            'data' => new UserResource($user)->handle(),
        ])->send(HttpCode::CREATED);
    }
}
