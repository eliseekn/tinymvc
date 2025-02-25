<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Client;

use Core\Enums\HttpMethod;

/**
 * Send asynchronous HTTP requests using curl.
 */
class Curl implements ClientInterface
{
    protected static array $response = [];

    /**
     * @link   https://niraeth.com/php-quick-function-for-asynchronous-multi-curl/
     *         https://stackoverflow.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request
     *         https://www.codexworld.com/post-receive-json-data-using-php-curl/
     *         https://stackoverflow.com/questions/13420952/php-curl-delete-request
     */
    public static function send(string $method, array|string $url, array $data = [], array $headers = [], bool $json = false): self
    {
        $response_headers = [];
        $response = [];
        $status_code = [];
        $curl_array = [];
        $curl_multi = curl_multi_init();

        $url = parse_array($url);

        foreach ($url as $key => $_url) {
            $curl_array[$key] = curl_init();
            $curl = $curl_array[$key];

            //set options
            curl_setopt($curl, CURLOPT_URL, $_url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT_MS, 300000);

            //set method
            if (strtoupper($method) !== HttpMethod::GET) {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            }

            // set json headers
            if ($json) {
                $headers = array_merge($headers, ['Content-Type' => 'application/json']);
            }

            //set data
            if (!empty($data)) {
                if ($json) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                } elseif (self::isMultipart($data)) {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    $headers = array_merge($headers, ['Content-Type' => 'multipart/form-data']);
                } else {
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                }
            }

            //set headers
            if (! empty($headers)) {
                $_headers = [];

                foreach ($headers as $_key => $value) {
                    $_headers[] = "$_key:$value";
                }

                curl_setopt($curl, CURLOPT_HTTPHEADER, $_headers);
            }

            //retrieves response headers
            curl_setopt(
                $curl,
                CURLOPT_HEADERFUNCTION,
                function ($curl, $header) use (&$response_headers, $key) {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);

                    if (count($header) < 2) {
                        return $len;
                    }

                    $response_headers[$key][strtolower(trim($header[0]))][] = trim($header[1]);

                    return $len;
                }
            );

            curl_multi_add_handle($curl_multi, $curl);
        }

        $i = null;

        do {
            curl_multi_exec($curl_multi, $i);

            if (curl_multi_errno($curl_multi)) {
                error_log(curl_error($curl_multi));
            }
        } while ($i);

        //retrieves response
        foreach ($curl_array as $key => $curl) {
            $response[$key] = curl_multi_getcontent($curl);
            $status_code[$key] = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            curl_multi_remove_handle($curl_multi, $curl);
        }

        curl_multi_close($curl_multi);

        self::$response['headers'] = $response_headers;
        self::$response['body'] = $response;
        self::$response['status_code'] = $status_code;

        return new self();
    }

    protected static function isMultipart(array $data): bool
    {
        foreach ($data as $value) {
            if ($value instanceof CURLFile) {
                return true;
            }
        }

        return false;
    }

    public static function get(array|string $url, array $headers = [], bool $json = false): self
    {
        return self::send(HttpMethod::GET, $url, [], $headers, json: $json);
    }

    public static function post(array|string $url, array $data = [], array $headers = [], bool $json = false): self
    {
        return self::send(HttpMethod::POST, $url, $data, $headers, $json);
    }

    public static function put(array|string $url, array $data = [], array $headers = [], bool $json = false): self
    {
        return self::send(HttpMethod::PUT, $url, $data, $headers, $json);
    }

    public static function delete(array|string $url, array $headers = [], bool $json = false): self
    {
        return self::send(HttpMethod::DELETE, $url, [], $headers, json: $json);
    }

    public static function options(array|string $url, array $data = [], array $headers = [], bool $json = false): self
    {
        return self::send(HttpMethod::OPTIONS, $url, $data, $headers, $json);
    }

    public static function patch(array|string $url, array $data = [], array $headers = [], bool $json = false): self
    {
        return self::send(HttpMethod::PATCH, $url, $data, $headers, $json);
    }

    public function getHeaders(): mixed
    {
        return self::$response['headers'];
    }

    public function getBody(): mixed
    {
        return self::$response['body'];
    }

    public function getStatusCode(): mixed
    {
        return self::$response['status_code'];
    }
}
