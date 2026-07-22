<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Middlewares;

use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\ViewResponse;

class CheckIfUserAdmin
{
    public function handle(): void
    {
        if (is_admin()) {
            return;
        }

        if (request()->isJson()) {
            new JsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => __('alert.forbidden'),
            ], HttpCode::FORBIDDEN)->send();
        }

        new ViewResponse(config('errors.views.403'))->send();
    }
}
