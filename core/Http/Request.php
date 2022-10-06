<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
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
    public function __construct() {}

    public function headers(?string $key = null, $default = null)
    {
        $header = !$key ? $_SERVER : ($_SERVER[$key] ?? '');
        return !$header ? $default : $header;
    }
    
    public function getHttpAuth(): array
    {
        $header = $this->headers('HTTP_AUTHORIZATION', '');
        return !$header ? [] : explode(' ', $header);
    }
    
    public function host()
    {
        return $this->headers('HTTP_HOST', '');
    }
    
    public function query(string $key, $default = null)
    {
        return $this->queries()[$key] ?? $default;
    }

    public function input(string $key, $default = null)
    {
        return $this->inputs()[$key] ?? $default;
    }

    public function file(string $key, $default = null)
    {
        return $this->files()[$key] ?? $default;
    }

    public function queries(): array
    {
        return $_GET;
    }

    public function inputs(): array
    {
        return array_merge($_POST, $this->raw());
    }

    public function files(): array
    {
        return $_FILES;
    }

    public function raw(): array
    {
        $data = [];
        parse_raw_http_request($data);

        return $data;
    }

    private function getSingleFile(string $input, array $allowed_ext = []): null|Uploader
    {
        if (!$this->file($input)) return null;
        
        return new Uploader([
            'name' => $this->file($input)['name'],
            'tmp_name' => $this->file($input)['tmp_name'],
            'size' => $this->file($input)['size'],
            'type' => $this->file($input)['type'],
            'error' => $this->file($input)['error']
        ], $allowed_ext);
    }

    /**
     * @return array<int, Uploader>
     */
    private function getMultipleFiles(string $input, array $allowed_ext = []): array
    {
        $files = [];

        if (!$this->file($input)) return $files;

        $count = is_array($this->file($input)['tmp_name']) ? count($this->file($input)['tmp_name']) : 1;

        for ($i = 0; $i < $count; $i++) {
            $files[] = new Uploader([
                'name' => $this->file($input)['name'][$i],
                'tmp_name' => $this->file($input)['tmp_name'][$i],
                'size' => $this->file($input)['size'][$i],
                'type' => $this->file($input)['type'][$i],
                'error' => $this->file($input)['error'][$i]
            ], $allowed_ext);
        }
        
        return $files;
    }
    
    public function getFiles(string $input, bool $multiple = false, array $allowed_ext = []): Uploader|array
    {
        return $multiple ? $this->getMultipleFiles($input, $allowed_ext)
            : $this->getSingleFile($input, $allowed_ext);
    }

    public function method(?string $value = null)
    {
        if (!$value) {
            return $this->headers('REQUEST_METHOD', '');
        }

        $_SERVER['REQUEST_METHOD'] = $value;
    }
    
    public function is(string $method): bool
    {
        return $this->method() === strtoupper($method);
    }

    public function fullUri(): string
    {
        $uri = $this->headers('REQUEST_URI', '');
        $uri = urldecode($uri);
        $uri = filter_var($uri, FILTER_SANITIZE_URL) === false ? $uri : filter_var($uri, FILTER_SANITIZE_URL);
        $uri = str_replace('//', '/', $uri);
 
        if ($uri !== '/') $uri = rtrim($uri, '/');

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

    public function uriContains(string $uri): string
    {
        return url_contains($uri);
    }
    
    public function remoteIP()
    {
        return $this->headers('REMOTE_ADDR', '');
    }

    public function hasQuery(string ...$items): bool
    {
        $result = false;

        foreach ($items as $item) {
            $result = !is_null($this->query($item));
        }

        return $result;
    }
    
    public function hasInput(string ...$items): bool
    {
        $result = false;

        foreach ($items as $item) {
            $result = !is_null($this->input($item));
        }

        return $result;
    }

    public function setInput(string $key, $value)
    {
        $_POST[$key] = $value;
    }

    public function setQuery(string $key, $value)
    {
        $_GET[$key] = $value;
    }

    public function filled(string ...$items): bool
    {
        $result = false;

        foreach ($items as $item) {
            $result = $this->hasInput($item) && !empty($item);
        }

        return $result;
    }

    public function only(string ...$items): array
    {
        $result = [];

        foreach ($items as $item) {
            if ($this->hasInput($item)) {
                $result = array_merge($result, [$item => $this->{$item}]);
            }
        }

        return $result;
    }
    
    public function except(string ...$items): array
    {
        $result = [];

        if (!$this->all()) return $result;

        foreach ($items as $item) {
            foreach ($this->all() as $key => $input) {
                if ($item !== $key) {
                    $result = array_merge($result, [$key => $input]);
                }
            }
        }

        return $result;
    }
    
    public function all(): array
    {
        $data = [];

        if ($this->inputs()) $data[] = $this->inputs();
        if ($this->queries()) $data[] = $this->queries();
        if ($this->files()) $data[] = $this->files();

        return $data;
    }

    public function validate(array $rules, array $messages = [], array $inputs = []): Validator
    {
        $inputs = empty($inputs) ? $this->inputs() : $inputs;

        return (new Validator($rules, $messages))->validate($inputs);
    }
}
