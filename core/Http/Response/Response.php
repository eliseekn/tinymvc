<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Response;

use Core\Http\Redirect;
use Core\Routing\View;

/**
 * Send HTTP response
 */
class Response implements ResponseInterface
{
    public function headers($name, ?string $value = null, int $code = 200)
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
    
    /**
     * @param string $data
     */
    public function send($data, array $headers = [], int $code = 200)
    {
        if (empty($data)) return;

        $this->headers(array_merge($headers, ['Content-Length' => strlen($data)]), null, $code);

        exit($data);
    }

    public function view(string $view, array $data = [], array $headers = [], int $code = 200)
    {
        $this->send(View::getContent($view, $data), $headers, $code);
    }

    public function redirect()
    {
        return new Redirect();
    }
}
