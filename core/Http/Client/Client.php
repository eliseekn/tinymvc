<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Client;

use Core\Enums\HttpMethod;
use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;

/**
 * Send concurrent HTTP requests using Symfony HTTP Client.
 */
class Client implements ClientInterface
{
    protected static array $response = [];

    public static function send(string $method, string $url, array $data = [], array $headers = [], bool $json = false): self
    {
        $client = HttpClient::create();
        $options = ['timeout' => 300];

        if ($json && empty($data)) {
            $headers['Content-Type'] = 'application/json';
        }

        if (! empty($headers)) {
            $options['headers'] = $headers;
        }

        if (! empty($data)) {
            if ($json) {
                $options['json'] = $data;
            } elseif (self::isMultipart($data)) {
                $formData = new FormDataPart($data);
                $options['headers'] = array_merge($options['headers'] ?? [], $formData->getPreparedHeaders()->toArray());
                $options['body'] = $formData->bodyToIterable();
            } else {
                $options['body'] = $data;
            }
        }

        try {
            $response = $client->request(strtoupper($method), $url, $options);

            self::$response['headers'] = $response->getHeaders(false);
            self::$response['body'] = $response->getContent(false);
            self::$response['status_code'] = $response->getStatusCode();
        } catch (Exception $e) {
            report($e);
        }

        return new self;
    }

    protected static function isMultipart(array $data): bool
    {
        foreach ($data as $value) {
            if ($value instanceof DataPart) {
                return true;
            }
        }

        return false;
    }

    public static function get(string $url, array $headers = [], bool $json = false): self
    {
        return self::send(HttpMethod::GET, $url, [], $headers, json: $json);
    }

    public static function post(string $url, array $data = [], array $headers = [], bool $json = false): self
    {
        return self::send(HttpMethod::POST, $url, $data, $headers, $json);
    }

    public static function put(string $url, array $data = [], array $headers = [], bool $json = false): self
    {
        return self::send(HttpMethod::PUT, $url, $data, $headers, $json);
    }

    public static function delete(string $url, array $headers = [], bool $json = false): self
    {
        return self::send(HttpMethod::DELETE, $url, [], $headers, json: $json);
    }

    public static function options(string $url, array $data = [], array $headers = [], bool $json = false): self
    {
        return self::send(HttpMethod::OPTIONS, $url, $data, $headers, $json);
    }

    public static function patch(string $url, array $data = [], array $headers = [], bool $json = false): self
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

    public function getBodyAsJson(): mixed
    {
        return json_decode(self::$response['body'], true);
    }

    public function getStatusCode(): mixed
    {
        return self::$response['status_code'];
    }
}
