<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Http;

use Exception;

/**
 * Send HTTP requests
 */
class Client
{
    /**
     * response request
     *
     * @var array
     */
    protected static $response = [];

    /**
     * send request to urls
     *
     * @param  string $method
     * @param  array $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json send data in json format
     * @return \Framework\Http\Client
     */
    public static function send(string $method, array $urls, array $headers = [], ?array $data = null, bool $json = false): self 
    {
        self::$response = curl($method, $urls, $headers, $data, $json);
        return new self();
    }

    /**
     * send GET request
     *
     * @param  array $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json send data in json
     * @return \Framework\Http\Client
     */
    public static function get(array $urls, array $headers = [], ?array $data = null, bool $json = false): self {
        return self::send('GET', $urls, $headers, $data, $json);
    }
    
    /**
     * send POST request
     *
     * @param  array $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json send data in json format
     * @return \Framework\Http\Client
     */
    public static function post(array $urls, array $headers = [], ?array $data = null, bool $json = false): self {
        return self::send('POST', $urls, $headers, $data, $json);
    }

    /**
     * send PUT request
     *
     * @param  array $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json send data in json format
     * @return \Framework\Http\Client
     */
    public static function put(array $urls, array $headers = [], ?array $data = null, bool $json = false): self {
        return self::send('PUT', $urls, $headers, $data, $json);
    }

    /**
     * send DELETE request
     *
     * @param  array $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json send data in json
     * @return \Framework\Http\Client
     */
    public static function delete(array $urls, array $headers = [], ?array $data = null, bool $json = false): self {
        return self::send('DELETE', $urls, $headers, $data, $json);
    }

    /**
     * send OPTIONS request
     *
     * @param  array $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json send data in json format
     * @return \Framework\Http\Client
     */
    public static function option(array $urls, array $headers = [], ?array $data = null, bool $json = false): self {
        return self::send('OPTIONS', $urls, $headers, $data, $json);
    }

    /**
     * send PATCH request
     *
     * @param  array $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json send data in json format
     * @return \Framework\Http\Client
     */
    public static function patch(array $urls, array $headers = [], ?array $data = null, bool $json = false): self {
        return self::send('PATCH', $urls, $headers, $data, $json);
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
    public function getHeader(string $field)
    {
        return $this->getHeaders()[$field] ?? '';
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
