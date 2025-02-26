<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Middlewares;

use App\Enums\UserRole;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Http\Request;
use Core\Http\Response;

class CheckUserAdmin
{
    public function handle(Request $request, Response $response): void
    {
        if (auth()->get('role') !== UserRole::ADMIN->value) {
            if ($request->isJson()) {
                $response->json([
                    'status' => ResponseStatus::ERROR,
                    'message' => __('alert.forbidden'),
                ])->send(HttpCode::FORBIDDEN);
            }

            $response->data(__('alert.forbidden'))->send(HttpCode::FORBIDDEN);
        }
    }
}
