<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Routing;

use Core\Enums\HttpCode;
use Core\Exceptions\FileNotFoundException;
use Core\Exceptions\InvalidJsonDataException;
use Core\Exceptions\InvalidResponseDataException;
use Core\Http\Validation\Validator\Validator;
use Exception;

class Controller
{
    public function __construct()
    {
    }

    public function redirectToUrl(string $uri, array $queries = []): void
    {
        response()->url($uri, $queries)->send();
    }

    public function redirectToRoute(string $route, array $params = []): void
    {
        response()->route($route, $params)->send();
    }

    public function redirectBack(): void
    {
        response()->back()->send();
    }

    public function render(string $view, array $data = []): void
    {
        response()->view($view, $data)->send(HttpCode::OK);
    }

    /**
     * @throws InvalidResponseDataException
     */
    public function response(string $data, int $code = HttpCode::OK): void
    {
        response()->data($data)->send($code);
    }

    /**
     * @throws InvalidJsonDataException
     */
    public function jsonResponse(array $data, int $code = HttpCode::OK): void
    {
        response()->json($data)->send($code);
    }

    /**
     * @throws FileNotFoundException
     */
    public function downloadResponse(string $filename, int $code = HttpCode::OK): void
    {
        response()->download($filename)->send($code);
    }

    /**
     * @throws Exception
     */
    public function validate(Validator $validator): array
    {
        return $validator->validate()->inputs();
    }
}
