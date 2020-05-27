<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Http;

/**
 * Response
 * 
 * Send HTTP responses
 */
class Response
{
    /**
     * send HTTP response
     *
     * @param  array $headers response headers
     * @param  mixed $body response body
     * @param  int $code response status code
     * @return void
     */
    public static function send(array $headers, $body, int $code = 200): void
    {
        //send response status code
        http_response_code($code);

        //send response headers
        if (!empty($headers)) {
            foreach ($headers as $name => $value) {
                header($name . ': ' . $value);
            }
        }

        //send response body
        if (!is_null($body)) {
            header('Content-Length: ' . strlen($body));
            echo $body;
        }

        exit();
    }

    /**
     * send HTTP response with json body
     *
     * @param  array $headers response headers
     * @param  mixed $body response body
     * @param  int $code response status code
     * @return void
     */
    public static function sendJson(array $headers, array $body, int $code = 200): void
    {
        if (!is_array($body) || !empty($body)) {
            return;
        }

        //send response status code
        http_response_code($code);

        //send response headers
        if (!empty($headers)) {
            foreach ($headers as $name => $value) {
                header($name . ': ' . $value);
            }
        }

        //generate json from body array
        $body = json_encode($body);

        //send json header
        header('Content-Type: application/json');
        header('Content-Length: ' . strlen($body));

        //send response body and exit
        exit($body);
    }
}
