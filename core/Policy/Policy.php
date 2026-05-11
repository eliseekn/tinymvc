<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Policy;

use Core\Database\Model;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;

class Policy
{
    public static function authorize(PolicyInterface $policy, string $action, Model $model): void
    {
        $bobl = call_user_func([$policy, $action], $model);

        if (! $bobl) {
            if (request()->isJson()) {
                response()->json([
                    'status' => ResponseStatus::ERROR,
                    'message' => 'Unauthorized',
                ])->send(HttpCode::UNAUTHORIZED);
            }

            response()->view(config('errors.views.401'))->send(HttpCode::UNAUTHORIZED);
        }
    }
}
