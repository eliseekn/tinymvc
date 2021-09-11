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
class JsonResponse extends BaseResponse implements ResponseInterface
{
    public function send($data, array $headers = [], int $code = 200)
    {
        if (!isset($data) or empty($data)) {
            return;
        }

        $data = json_encode($data);

        $this->headers(array_merge($headers, [
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($data),
        ]), null, $code);

        exit($data);
    }
}
