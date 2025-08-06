<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\Api\v1\User;

use App\Database\Models\User;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;

final class StoreUseCase
{
    public function handle(array $data): void
    {
        $data['password'] = bcrypt($data['password']);

        if (! User::factory()->create($data)) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'message' => 'Failed to create user',
            ])->send(HttpCode::INTERNAL_SERVER_ERROR);
        }

        response()->json([
            'status' => ResponseStatus::SUCCESS,
            'message' => 'User created',
        ])->send(HttpCode::CREATED);
    }
}
