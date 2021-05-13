<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Http;

use Framework\Support\Uploader;

/**
 * Handle HTTP requests
 */
class Request
{    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        foreach ($this->all() as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * retrieves headers
     *
     * @param  string|null $key
     * @param  mixed $default
     * @return mixed
     */
    public function headers(?string $key = null, $default = null)
    {
        $header = is_null($key) ? $_SERVER : ($_SERVER[$key] ?? '');
        return empty($header) || is_null($header) ? $default : $header;
    }
    
    /**
     * retrieves http authentication credentials
     *
     * @return array
     */
    public function http_auth(): array
    {
        $header = $this->headers('HTTP_AUTHORIZATION', '');
        return empty($header) ? [] : explode(' ', $header);
    }
    
    /**
     * retrieves queries
     *
     * @param  string|null $key
     * @param  mixed $default
     * @return mixed
     */
    public function queries(?string $key = null, $default = null)
    {
        $query = is_null($key) ? $_GET : ($_GET[$key] ?? '');
        return empty($query) || is_null($query) ? $default : $query;
    }

    /**
     * retrieves inputs
     *
     * @param  string|null $key
     * @param  mixed $default
     * @return mixed
     */
    public function inputs(?string $key = null, $default = null)
    {
        $input = is_null($key) ? $_POST : ($_POST[$key] ?? '');
        return empty($input) || is_null($input) ? $default : $input;
    }

    /**
     * retrieves raw data
     *
     * @return mixed
     */
    public function rawData()
    {
        return file_get_contents('php://input');
    }

    /**
     * retrieves single file
     *
     * @param  string $input
     * @param  array $allowed_extensions
     * @return \Framework\Support\Uploader
     */
    private function getSingleFile(string $input, array $allowed_extensions = []): \Framework\Support\Uploader
    {
        return new Uploader([
            'name' => $_FILES[$input]['name'],
            'tmp_name' => $_FILES[$input]['tmp_name'],
            'size' => $_FILES[$input]['size'],
            'type' => $_FILES[$input]['type'],
            'error' => $_FILES[$input]['error']
        ], $allowed_extensions);
    }

    /**
     * retrieves multiple files
     *
     * @param  string $input
     * @param  array $allowed_extensions
     * @return array
     */
    private function getMultipleFiles(string $input, array $allowed_extensions = []): array
    {
        $files = [];

        if (!isset($_FILES[$input]) || empty($_FILES[$input])) {
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
    
    /**
     * retrieves files
     *
     * @param  string $input
     * @param  array $allowed_extensions
     * @param  bool $multiple
     * @return \Framework\Support\Uploader|array
     */
    public function files(string $input, array $allowed_extensions = [], bool $multiple = false)
    {
        return $multiple 
            ? $this->getMultipleFiles($input, $allowed_extensions) 
            : $this->getSingleFile($input, $allowed_extensions);
    }

    /**
     * retrieves request method
     *
     * @param  $value string
     * @return string|void
     */
    public function method(?string $value = null)
    {
        return is_null($value) ? $this->headers('REQUEST_METHOD', '') : $_SERVER['REQUEST_METHOD'] = $value;
    }
    
    /**
     * check request method
     *
     * @param  string $method
     * @return bool
     */
    public function is(string $method): bool
    {
        return $this->method() === $method;
    }

    /**
     * retrieves full uri
     *
     * @return string
     */
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

    /**
     * retrieves partial uri
     *
     * @return string
     */
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
     * retrieves ip address
     *
     * @return string
     */
    public function remoteIP(): string
    {
        return $this->headers('REMOTE_ADDR', '');
    }
    
    /**
     * check if request item exists
     *
     * @param  string[] $items
     * @return bool
     */
    public function has(string ...$items): bool
    {
        $result = false;

        foreach ($items as $item) {
            $result = isset($this->{$item});
        }

        return $result;
    }
    
    /**
     * check if request item exists and not empty
     *
     * @param  string[] $items
     * @return bool
     */
    public function filled(string ...$items): bool
    {
        $result = $this->has(implode(',', $items));

        if (!$result) {
            return false;
        } 

        foreach ($items as $item) {
            $result = !empty($this->{$item}) || !is_null($this->{$item});
        }

        return $result;
    }

    /**
     * retrieves value of POST/GET request or return default
     *
     * @param  string $item
     * @param  mixed $default
     * @return mixed
     */
    public function get(string $item, $default = null)
    {
        return $this->filled($item) ? $this->{$item} : $default;
    }

    /**
     * set value of POST/GET request
     *
     * @param  string $item
     * @param  mixed $value
     * @return void
     */
    public function set(string $item, $value): void
    {
        if (isset($_POST[$item])) {
            $_POST[$item] = $value;
        }

        if (isset($_GET[$item])) {
            $_GET[$item] = $value;
        }

        $this->{$item} = $value;
    }
    
    /**
     * retrieves certains items only
     *
     * @param  string[] $items
     * @return array
     */
    public function only(string ...$items): array
    {
        $result = [];

        foreach ($items as $item) {
            if ($this->has($item)) {
                $result = array_merge($result, [
                    $item => $this->{$item}
                ]);
            }
        }

        return $result;
    }
    
    /**
     * retrieves all items except certains
     *
     * @param  string[] $items
     * @return array
     */
    public function except(string ...$items): array
    {
        $result = [];

        if (empty($this->all())) {
            return $result;
        }

        foreach ($items as $item) {
            foreach ($this->all() as $key => $input) {
                if ($item !== $key) {
                    $result = array_merge($result, [
                        $key => $input
                    ]);
                }
            }
        }

        return $result;
    }
    
    /**
     * retrieves all items from POST and GET
     *
     * @return array
     */
    public function all(): array
    {
        $all = [];

        if (!is_null($this->inputs())) {
            $all = array_merge($all, $this->inputs());
        }

        if (!is_null($this->queries())) {
            $all = array_merge($all, $this->queries());
        }

        return $all;
    }
}
