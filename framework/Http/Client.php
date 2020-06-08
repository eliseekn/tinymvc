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

use Exception;

/**
 * Client
 * 
 * Send HTTP requests
 */
class Client
{
    /**
     * url to send request
     *
     * @var array
     */
    protected static $urls = [];

    /**
     * response request
     *
     * @var string
     */
    protected static $response = [];

    /**
     * send request to url
     *
     * @param  string $method method name
     * @param  array|null $data data to send
     * @param  bool $json_data send data in json format (only for POST request)
     * @return mixed
     */
    public static function send(
        string $method = 'GET',
        array $urls,
        $headers = [],
        ?array $data = null,
        bool $json_data = false
    ) {
        self::$urls = $urls;

        if (empty(self::$urls)) {
            throw new Exception('Cannot send HTTP request to empty url.');
        }

        self::$response = curl($method, self::$urls, $headers, $data, $json_data);
        return new self();
    }

    /**
     * retrieves request headers
     *
     * @param  string $field name of $_SERVER array field
     * @return mixed returns field value or empty string
     */
    public function getHeaders(string $field = '')
    {
        return empty($field) ? self::$response['headers'] : self::$response['headers'][$field] ?? '';
    }

    /**
     * get response body
     *
     * @return mixed
     */
    public function getBody()
    {
        return self::$response['body'];
    }
}
