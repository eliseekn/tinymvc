<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\HTTP;

use Exception;

/**
 * Send HTTP requests
 */
class Client
{
    /**
     * response request
     *
     * @var string
     */
    protected static $response = [];

    /**
     * send request to url
     *
     * @param  string $method
     * @param  array $urls
     * @param  array $hedears
     * @param  array|null $data data to send
     * @param  bool $json_data send data in json format
     * @return mixed
     */
    public static function send(
        string $method,
        array $urls,
        array $headers = [],
        ?array $data = null,
        bool $json_data = false
    ) {
        if (empty($urls)) {
            throw new Exception('Cannot send HTTP request to empty url');
        }

        self::$response = curl($method, $urls, $headers, $data, $json_data);
        return new self();
    }

    /**
     * send GET request
     *
     * @param  array $urls
     * @param  array $hedears
     * @param  array|null $data data to send
     * @param  bool $json_data send data in json
     * @return mixed
     */
    public static function get(
        array $urls,
        array $headers = [],
        ?array $data = null,
        bool $json_data = false
    ) {
        return self::send('GET', $urls, $headers, $data, $json_data);
    }
    
    /**
     * send POST request
     *
     * @param  array $urls
     * @param  array $hedears
     * @param  array|null $data data to send
     * @param  bool $json_data send data in json format
     * @return mixed
     */
    public static function post(
        array $urls,
        array $headers = [],
        ?array $data = null,
        bool $json_data = false
    ) {
        return self::send('POST', $urls, $headers, $data, $json_data);
    }

    /**
     * send PUT request
     *
     * @param  array $urls
     * @param  array $hedears
     * @param  array|null $data data to send
     * @param  bool $json_data send data in json format
     * @return mixed
     */
    public static function put(
        array $urls,
        array $headers = [],
        ?array $data = null,
        bool $json_data = false
    ) {
        return self::send('PUT', $urls, $headers, $data, $json_data);
    }

    /**
     * send DELETE request
     *
     * @param  array $urls
     * @param  array $hedears
     * @param  array|null $data data to send
     * @param  bool $json_data send data in json
     * @return mixed
     */
    public static function delete(
        array $urls,
        array $headers = [],
        ?array $data = null,
        bool $json_data = false
    ) {
        return self::send('DELETE', $urls, $headers, $data, $json_data);
    }

    /**
     * send OPTIONS request
     *
     * @param  array $urls
     * @param  array $hedears
     * @param  array|null $data data to send
     * @param  bool $json_data send data in json format
     * @return mixed
     */
    public static function option(
        array $urls,
        array $headers = [],
        ?array $data = null,
        bool $json_data = false
    ) {
        return self::send('OPTIONS', $urls, $headers, $data, $json_data);
    }

    /**
     * send PATCH request
     *
     * @param  array $urls
     * @param  array $hedears
     * @param  array|null $data data to send
     * @param  bool $json_data send data in json format
     * @return mixed
     */
    public static function patch(
        array $urls,
        array $headers = [],
        ?array $data = null,
        bool $json_data = false
    ) {
        return self::send('PATCH', $urls, $headers, $data, $json_data);
    }

    /**
     * retrieves single response header
     *
     * @return mixed
     */
    public function getHeaders()
    {
        return self::$response['headers'];
    }

    /**
     * retrieves response headers
     *
     * @param  string $field
     * @return mixed returns field value or empty string
     */
    public function getHeader(string $field): string
    {
        return $this->getHeaders()[$field];
    }

    /**
     * retrieves response body
     *
     * @return mixed
     */
    public function getBody()
    {
        return self::$response['body'];
    }
}
