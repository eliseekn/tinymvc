<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Response\Traits;

use Core\Enums\HttpCode;
use Core\Enums\ResponseStatus;
use Core\Http\Response\BaseResponse;
use Core\Http\Response\DownloadResponse;
use Core\Http\Response\JsonResponse;
use Core\Http\Response\RedirectResponse;
use Core\Http\Response\Response;
use Core\Http\Response\ViewResponse;

/**
 * Http response trait.
 */
trait HttpResponse
{
    public function response(string $data, int $code): BaseResponse
    {
        return new Response($data, $code);
    }

    public function jsonResponse(array $data, int $code): BaseResponse
    {
        return new JsonResponse($data, $code);
    }

    public function errorJsonResponse(string|array $message, int $code = HttpCode::INTERNAL_SERVER_ERROR): BaseResponse
    {
        if (is_array($message)) {
            return new JsonResponse(array_merge(['status' => ResponseStatus::ERROR], $message), $code);
        }

        return new JsonResponse([
            'status' => ResponseStatus::ERROR,
            'message' => $message,
        ], $code);
    }

    public function successJsonResponse(string|array $message, int $code = HttpCode::OK): BaseResponse
    {
        if (is_array($message)) {
            return new JsonResponse(array_merge(['status' => ResponseStatus::SUCCESS], $message), $code);
        }

        return new JsonResponse([
            'status' => ResponseStatus::SUCCESS,
            'message' => $message,
        ], $code);
    }

    public function downloadResponse(string $filename, int $code = HttpCode::OK): BaseResponse
    {
        return new DownloadResponse($filename, $code);
    }

    public function viewResponse(string $view, array $data = []): BaseResponse
    {
        return new ViewResponse($view, $data);
    }

    public function redirectResponse(): RedirectResponse
    {
        return new RedirectResponse;
    }
}
