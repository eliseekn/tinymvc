<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Responses;

/**
 * Send HTTP response
 */
class BaseResponse implements ResponseInterface
{
    public function headers($name, string $value = null, int $code = 200)
    {
        if (config('app.env') === 'test') {
            header('Session:' . json_encode($_SESSION));
        }

        http_response_code($code);

        if (!is_array($name)) {
            header($name . ':' . $value);
            return;
        }

        foreach ($name as $k => $v) {
            header($k . ':' . $v);
        }
    }
    
    public function send($body, array $headers = [], int $code = 200)
    {
        if (!isset($body) or empty($body)) {
            return;
        }

        $this->headers(array_merge($headers, [
            'Content-Length' => strlen($body)
        ]), null, $code);

        exit($body);
    }
}
