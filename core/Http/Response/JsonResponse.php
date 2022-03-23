<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Response;

use Core\Exceptions\InvalidJsonDataException;

/**
 * Send response with JSON data
 */
class JsonResponse extends Response implements ResponseInterface
{
    /**
     * @param array|string $data
     */
    public function send($data, array $headers = [], int $code = 200)
    {
        if (is_null($data)) throw new InvalidJsonDataException();

        if (!is_array($data)) {
            if (!is_string($data)) throw new InvalidJsonDataException();

            $data = [$data];
        }

        $data = json_encode($data);

        $this->headers(array_merge($headers, [
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($data),
        ]), null, $code);

        exit($data);
    }
}
