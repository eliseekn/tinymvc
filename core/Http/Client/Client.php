<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Client;

use Core\Enums\HttpMethod;
use Core\Support\Logger;
use Exception;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Mime\Part\DataPart;
use Symfony\Component\Mime\Part\Multipart\FormDataPart;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Send concurrent HTTP requests using Symfony HTTP Client.
 */
class Client implements ClientInterface
{
    protected static ResponseInterface $response;

    public static function send(string $method, string $url, array $data = [], array $headers = [], bool $json = false): self
    {
        $client = HttpClient::create();
        $options = ['timeout' => 300, 'max_redirects' => 0];

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
            self::$response = $client->request(strtoupper($method), $url, $options);
        } catch (Exception $e) {
            Logger::exception($e);
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
        return self::$response->getHeaders(false);
    }

    public function getBody(): mixed
    {
        return self::$response->getContent(false);

    }

    public function getBodyAsJson(): mixed
    {
        return json_decode(self::$response->getContent(false), true);
    }

    public function getStatusCode(): mixed
    {
        return self::$response->getStatusCode();
    }
}
