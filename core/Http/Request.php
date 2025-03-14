<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http;

use Core\Exceptions\RouteParameterException;
use Core\Exceptions\RoutesPathsNotDefinedException;
use Core\Http\Routing\Route;
use Core\Support\Uploader;

/**
 * Handle HTTP requests.
 */
class Request
{
    public function headers(?string $key = null, $default = null): mixed
    {
        $header = is_null($key) ? $_SERVER : ($_SERVER[$key] ?? '');

        return empty($header) ? $default : $header;
    }

    public function getHttpAuth(): array
    {
        $header = $this->headers('HTTP_AUTHORIZATION', '');

        return empty($header) ? [] : explode(' ', $header);
    }

    public function isJson(): bool
    {
        return $this->headers('CONTENT_TYPE') === 'application/json';
    }

    public function host(): mixed
    {
        return $this->headers('HTTP_HOST', '');
    }

    public function queries(?string $key = null, $default = null): array|string|int|bool|null
    {
        $result = $_GET;

        foreach ($result as $field => $value) {
            if (! is_null($value)) {
                $result[$field] = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
            }
        }

        if (is_null($key)) {
            return $result;
        }

        return empty($result[$key]) ? $default : $result[$key];
    }

    public function inputs(?string $key = null, $default = null): array|string|int|null
    {
        $result = array_merge($_POST, $this->raw());

        foreach ($result as $field => $value) {
            if (! is_null($value)) {
                $result[$field] = htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
            }
        }

        if (is_null($key)) {
            return $result;
        }

        return empty($result[$key]) ? $default : $result[$key];
    }

    public function files(string $input, array $allowed_extensions = []): Uploader|array
    {
        $files = [];

        if (empty($_FILES[$input])) {
            return $files;
        }

        $count = is_array($_FILES[$input]['tmp_name']) ? count($_FILES[$input]['tmp_name']) : 1;

        if ($count === 1) {
            return new Uploader([
                'name' => $_FILES[$input]['name'],
                'tmp_name' => $_FILES[$input]['tmp_name'],
                'size' => $_FILES[$input]['size'],
                'type' => $_FILES[$input]['type'],
                'error' => $_FILES[$input]['error'],
            ], $allowed_extensions);
        }

        for ($i = 0; $i < $count; $i++) {
            $files[] = new Uploader([
                'name' => $_FILES[$input]['name'][$i],
                'tmp_name' => $_FILES[$input]['tmp_name'][$i],
                'size' => $_FILES[$input]['size'][$i],
                'type' => $_FILES[$input]['type'][$i],
                'error' => $_FILES[$input]['error'][$i],
            ], $allowed_extensions);
        }

        return $files;
    }

    public function method(?string $value = null): string
    {
        if (is_null($value)) {
            return strtoupper($this->headers('REQUEST_METHOD', ''));
        }

        $_SERVER['REQUEST_METHOD'] = $value;

        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    public function fullUri(): string
    {
        $uri = $this->headers('REQUEST_URI', '');
        $uri = urldecode($uri);
        $uri = filter_var($uri, FILTER_SANITIZE_URL) === false ? $uri : filter_var($uri, FILTER_SANITIZE_URL);
        $uri = str_replace('//', '/', $uri);

        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        return $uri;
    }

    public function uri(): string
    {
        $uri = $this->fullUri();

        //removes queries from uri
        if (strpos($uri, '?')) {
            $uri = substr($uri, strpos($uri, '/'), strpos($uri, '?'));
        }

        return $uri;
    }

    /**
     * @throws RoutesPathsNotDefinedException
     * @throws RouteParameterException
     */
    public function routeParam(string $name): mixed
    {
        $result = [];
        $routes = Route::getAll();

        foreach ($routes as $route => $options) {
            $routeParams = $options['parameters'] ?? [];

            $route = route_parameters_to_regex($route, $routeParams, $result);

            if (preg_match('#^' . $route . '$#', $this->method() . ' ' . $this->uri(), $matches)) {
                array_shift($matches);
                $paramKeys = array_keys($result);

                foreach ($matches as $index => $value) {
                    if ($value !== '') {
                        $result[$paramKeys[$index]] = $value;
                        break;
                    }
                }

                break;
            }
        }

        return $result[$name] ?? null;
    }

    public function uriContains(string $uri): bool
    {
        return url_contains($uri);
    }

    public function ip(): string
    {
        return $this->headers('REMOTE_ADDR', '');
    }

    public function hasQuery(array|string $items): bool
    {
        $items = parse_array($items);
        $result = false;

        foreach ($items as $item) {
            $result = ! is_null($this->queries($item));
        }

        return $result;
    }

    public function hasInput(array|string $items): bool
    {
        $items = parse_array($items);
        $result = false;

        foreach ($items as $item) {
            $result = ! is_null($this->inputs($item));
        }

        return $result;
    }

    public function filled(array|string $items): bool
    {
        $items = parse_array($items);
        $result = $this->hasInput($items);

        if (! $result) {
            return false;
        }

        foreach ($items as $item) {
            $result = ! empty($this->inputs($item));
        }

        return $result;
    }

    public function setInput(string $item, $value): void
    {
        if (isset($_POST[$item])) {
            $_POST[$item] = $value;
        }
    }

    public function setQuery(string $item, $value): void
    {
        if (isset($_GET[$item])) {
            $_GET[$item] = $value;
        }
    }

    public function only(array|string $items): array
    {
        $items = parse_array($items);
        $result = [];

        foreach ($items as $item) {
            if ($this->hasInput($item)) {
                $result = array_merge($result, [$item => $this->inputs($item)]);
            }
        }

        return $result;
    }

    public function except(array|string $items): array
    {
        $items = parse_array($items);
        $result = [];

        if (empty($this->inputs())) {
            return $result;
        }

        foreach ($items as $item) {
            foreach ($this->inputs() as $key => $input) {
                if ($item !== $key) {
                    $result = array_merge($result, [$key => $input]);
                }
            }
        }

        return $result;
    }

    /**
     * Parse raw HTTP request data.
     *
     * Pass in $a_data as an array. This is done by reference to avoid copying
     * the data around too much.
     *
     * Any files found in the request will be added by their field name to the
     * $data['files'] array.
     *
     * @ref: http://www.chlab.ch/blog/archives/webdevelopment/manually-parse-raw-http-data-php
     *
     * @return array Associative array of request data
     */
    public function raw(): array
    {
        $data = [];
        $input = file_get_contents('php://input');

        if (! isset($_SERVER['CONTENT_TYPE'])) {
            parse_str(urldecode($input), $data);

            return $data;
        }

        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $data = json_decode($input, true);

            if (is_null($data)) {
                $data = [];
            }

            return $data;
        }

        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);

        if (! count($matches)) {
            parse_str(urldecode($input), $data);

            return $data;
        }

        $boundary = $matches[1];

        $a_blocks = preg_split("/-+$boundary/", $input);
        array_pop($a_blocks);

        foreach ($a_blocks as $id => $block) {
            if (empty($block)) {
                continue;
            }

            if (str_contains($block, 'application/octet-stream')) {
                if (preg_match('/name="([^"]+)"; filename="([^"]+)"/', $block, $fileMatches)) {
                    $fieldName = $fileMatches[1];
                    $fileName = $fileMatches[2];

                    $fileContent = substr($block, strpos($block, "\r\n\r\n") + 4);
                    $fileContent = substr($fileContent, 0, -2); // Remove trailing CRLF

                    $tmpFilePath = tempnam(sys_get_temp_dir(), 'php');
                    file_put_contents($tmpFilePath, $fileContent);

                    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($fileInfo, $tmpFilePath);
                    finfo_close($fileInfo);

                    $_FILES[$fieldName] = [
                        'name' => get_file_basename($fileName),
                        'type' => $mimeType,
                        'tmp_name' => $tmpFilePath,
                        'error' => 0,
                        'size' => filesize($tmpFilePath),
                    ];
                }

                $data = [];
            } else {
                preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
                $data[$matches[1]] = $matches[2];
            }
        }

        return $data;
    }
}
