<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http;

use Core\Http\Validator\Validator;
use Core\Support\Uploader;

/**
 * Handle HTTP requests
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
    
    public function host(): mixed
    {
        return $this->headers('HTTP_HOST', '');
    }
    
    public function queries(?string $key = null, $default = null): mixed
    {
        $query = is_null($key) ? $_GET : ($_GET[$key] ?? '');
        return empty($query) ? $default : $query;
    }

    public function inputs(?string $key = null, $default = null): mixed
    {
        $_POST = array_merge($_POST, $this->raw());
        $input = is_null($key) ? $_POST : ($_POST[$key] ?? '');
        return empty($input) ? $default : $input;
    }

    public function raw(): array
    {
        $data = [];
        parse_raw_http_request($data);
        return $data;
    }

    private function getSingleFile(string $input, array $allowed_extensions = []): Uploader|null
    {
        if (empty($_FILES[$input])) {
            return null;
        }
        
        return new Uploader([
            'name' => $_FILES[$input]['name'],
            'tmp_name' => $_FILES[$input]['tmp_name'],
            'size' => $_FILES[$input]['size'],
            'type' => $_FILES[$input]['type'],
            'error' => $_FILES[$input]['error']
        ], $allowed_extensions);
    }

    private function getMultipleFiles(string $input, array $allowed_extensions = []): array
    {
        $files = [];

        if (empty($_FILES[$input])) {
            return $files;
        }

        $count = is_array($_FILES[$input]['tmp_name']) ? count($_FILES[$input]['tmp_name']) : 1;

        for ($i = 0; $i < $count; $i++) {
            $files[] = new Uploader([
                'name' => $_FILES[$input]['name'][$i],
                'tmp_name' => $_FILES[$input]['tmp_name'][$i],
                'size' => $_FILES[$input]['size'][$i],
                'type' => $_FILES[$input]['type'][$i],
                'error' => $_FILES[$input]['error'][$i]
            ], $allowed_extensions);
        }
        
        return $files;
    }
    
    public function files(string $input, bool $multiple = false, array $allowed_extensions = []): array|Uploader|null
    {
        return $multiple
            ? $this->getMultipleFiles($input, $allowed_extensions)
            : $this->getSingleFile($input, $allowed_extensions);
    }

    public function method(?string $value = null)
    {
        if (is_null($value)) {
            return $this->headers('REQUEST_METHOD', '');
        }

        $_SERVER['REQUEST_METHOD'] = $value;
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
            $result = !is_null($this->queries($item));
        }

        return $result;
    }
    
    public function hasInput(array|string $items): bool
    {
        $items = parse_array($items);
        $result = false;

        foreach ($items as $item) {
            $result = !is_null($this->inputs($item));
        }

        return $result;
    }
    
    public function filled(array|string $items): bool
    {
        $items = parse_array($items);
        $result = $this->hasInput($items);

        if (!$result) {
            return false;
        }

        foreach ($items as $item) {
            $result = !empty($this->inputs($item));
        }

        return $result;
    }

    public function get(string $item, $default = null): mixed
    {
        return $this->filled($item) ? $this->inputs($item) : $default;
    }

    public function set(string $item, $value): void
    {
        if (isset($_POST[$item])) {
            $_POST[$item] = $value;
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

    public function validate(array $rules, array $messages = [], array $inputs = []): Validator
    {
        $inputs = empty($inputs) ? $this->inputs() : $inputs;
        return (new Validator($rules, $messages))->validate($inputs);
    }
}
