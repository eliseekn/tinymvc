<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Event\Events\ModelNotFound;

use Core\Enums\Alert\MessageType;
use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\RedirectResponse;

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
            new JsonResponse([
                'status' => ResponseStatus::ERROR,
                'data' => $message,
            ], HttpCode::NOT_FOUND)->send();
        }

        new RedirectResponse()
            ->toBack()
            ->withToast(MessageType::ERROR, $message)
            ->setStatusCode()
            ->send();
    }
}
