<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Responses;

/**
 * Send response with JSON data
 */
class JsonResponse extends BasicResponse
{
    public function send($body, array $headers = [], int $code = 200)
    {
        if (!isset($body) or empty($body)) {
            return;
        }

        $body = json_encode($body);

        $this->headers(array_merge($headers, [
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($body),
        ]), null, $code);

        exit($body);
    }
}
