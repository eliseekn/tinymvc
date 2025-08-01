<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\Api\v1\Auth;

use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Http\Auth;
use Core\Support\UseCase;

final class LogoutUseCase extends UseCase
{
    public function handle(): void
    {
        if (! Auth::deleteToken($this->request)) {
            $this->response->json([
                'status' => ResponseStatus::ERROR,
                'message' => 'Failed to logout',
            ])->send(HttpCode::INTERNAL_SERVER_ERROR);
        }

        $this->response->json([
            'status' => ResponseStatus::SUCCESS,
            'message' => 'Logout successfully',
        ])->send(HttpCode::OK);
    }
}
