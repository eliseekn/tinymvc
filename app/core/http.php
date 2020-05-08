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

/**
 * HttpRequests
 * 
 * Handle HTTP requests
 */
class HttpRequests
{    
    /**
     * retrieves request headers
     *
     * @param  string $field name of $_SERVER array field
     * @return mixed returns $_SERVER array, field value or empty string
     */
    public static function headers(string $field = '')
    {
        return empty($field) ? $_SERVER : $_SERVER[$field] ?? '';
    }
    
    /**
     * retrieves request get method
     *
     * @param  string $field name of $_GET array field
     * @return mixed returns $_GET array, field value or empty string
     */
    public static function get(string $field = '')
    {
        return empty($field) ? $_GET : $_GET[$field] ?? '';
    }

    /**
     * retrieves request post method
     *
     * @param  string $field name of $_POST array field
     * @return mixed returns $_POST array, field value or empty string
     */
    public static function post(string $field = '')
    {
        return empty($field) ? $_POST : $_POST[$field] ?? '';
    }

    /**
     * retrieves raw data from requests
     *
     * @return mixed data content or false
     */
    public static function data()
    {
        return file_get_contents('php://input');
    }
}

/**
 * HttpResponses
 * 
 * Send HTTP responses
 */
class HttpResponses
{    
    /**
     * send HTTP response
     *
     * @param  array $headers respnse headers
     * @param  mixed $body response body
     * @param  int $code response status code
     * @return void
     */
    public static function send(array $headers, $body, int $code): void
    {
        if (empty($headers) || empty($code)) {
            return;
        }

        //send response status code
        http_response_code($code);

        //send headers
        foreach ($headers as $name => $value) {
            header($name . ': ' . $value);
        }

        //send content lenght header
        if (strlen($body) > 0) {
            header('Content-Length: ' . strlen($body));
        }

        //send response body
        if ($body) {
            echo $body;
        }
    }
}
