<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\UseCases\Api\v1\User;

use App\Database\Models\User;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Support\UseCase;

class StoreUseCase extends UseCase
{
    public function handle(array $data): void
    {
        $data['password'] = hash_pwd($data['password']);

        if (! User::factory()->create($data)) {
            $this->response->json([
                'status' => ResponseStatus::ERROR,
                'message' => 'Failed to create user',
            ])->send(HttpCode::INTERNAL_SERVER_ERROR);
        }

        $this->response->json([
            'status' => ResponseStatus::SUCCESS,
            'message' => 'User created',
        ])->send(HttpCode::CREATED);
    }
}
