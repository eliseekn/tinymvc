<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Response;

use Exception;

/**
 * Send response with JSON data
 * 
 * @throws Exception
 */
class JsonResponse extends Response implements ResponseInterface
{
    /**
     * @param array|string $data
     */
    public function send($data, array $headers = [], int $code = 200)
    {
        if (is_null($data)) {
            throw new Exception('Invalid data');
        }

        if (!is_array($data)) {
            if (!is_string($data)) {
                throw new Exception('Invalid data');
            }

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
