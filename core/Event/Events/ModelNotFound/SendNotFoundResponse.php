<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Event\Events\ModelNotFound;

use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Support\Alert;

class SendNotFoundResponse
{
    public function __invoke(ModelNotFoundEvent $event): void
    {
        $message = __('alert.model_not_found', [
            'table' => $event->table,
            'column' => $event->column,
            'value' => $event->value,
        ]);

        if (request()->isJson()) {
            response()->json([
                'status' => ResponseStatus::ERROR,
                'data' => $message,
            ])->send(HttpCode::NOT_FOUND);
        }

        Alert::toast($message)->error();
        response()->back()->send();
    }
}
