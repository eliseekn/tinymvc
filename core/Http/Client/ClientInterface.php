<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Client;

interface ClientInterface
{
    public static function send(string $method, string $url, array $data = [], array $headers = [], bool $json = false);

    public static function get(string $url, array $headers = [], bool $json = false);

    public static function post(string $url, array $data = [], array $headers = [], bool $json = false);

    public static function put(string $url, array $data = [], array $headers = [], bool $json = false);

    public static function delete(string $url, array $headers = [], bool $json = false);

    public static function options(string $url, array $data = [], array $headers = [], bool $json = false);

    public static function patch(string $url, array $data = [], array $headers = [], bool $json = false);

    public function getHeaders();

    public function getBody();

    public function getBodyAsJson();

    public function getStatusCode();
}
