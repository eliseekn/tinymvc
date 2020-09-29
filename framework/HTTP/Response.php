<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\HTTP;

/**
 * Send HTTP responses
 */
class Response
{
    /**
     * send HTTP response
     *
     * @param  array $headers
     * @param  mixed $body
     * @param  int $status_code
     * @return void
     */
    public static function send(array $headers, $body, int $status_code = 200): void
    {
        if (!isset($body)) {
            return;
        }
        
        //send response status code
        http_response_code($status_code);

        //send response headers
        if (!empty($headers)) {
            foreach ($headers as $name => $value) {
                header($name . ': ' . $value);
            }
        }

        //set content length header
        header('Content-Length: ' . strlen($body));

        //send response body
        exit($body);
    }

    /**
     * send HTTP response with json body
     *
     * @param  array $headers
     * @param  array $body
     * @param  int $status_code
     * @return void
     */
    public static function sendJson(array $headers, array $body, int $status_code = 200): void
    {
        if (empty($body)) {
            return;
        }

        //send response status code
        http_response_code($status_code);

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
    
    /**
     * send HTTP headers only
     *
     * @param  array $headers
     * @param  int $status_code
     * @return void
     */
    public static function sendHeaders(array $headers, int $status_code = 200): void
    {
        //send response status code
        http_response_code($status_code);

        //send response headers
        foreach ($headers as $name => $value) {
            header($name . ': ' . $value);
        }
    }
}
