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

        if (empty($header) && !is_null($default)) {
            $header = $default;
        }

        return $header;
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

        if (empty($query) && !is_null($default)) {
            $query = $default;
        }

        return $query;
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

        if (empty($input) && !is_null($default)) {
            $input = $default;
        }

        return $input;
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
    private function getFile(string $input, array $allowed_extensions = []): \Framework\Support\Uploader
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
    private function getFiles(string $input, array $allowed_extensions = []): array
    {
        $files = [];

        if (isset($_FILES[$input]) && !empty($_FILES[$input])) {
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
        }
        
        return $files;
    }
    
    /**
     * retrieves files
     *
     * @param  string $input
     * @param  array $allowed_extensions
     * @param  bool $mutliple
     * @return \Framework\Support\Uploader|array
     */
    public function files(string $input, array $allowed_extensions = [], bool $mutliple = false)
    {
        return $mutliple ? $this->getFiles($input, $allowed_extensions) : $this->getFile($input, $allowed_extensions);
    }

    /**
     * retrieves request method
     *
     * @param  $value string
     * @return string|void
     */
    public function method(?string $value = null)
    {
        return is_null($value) ? $this->headers('REQUEST_METHOD') : $_SERVER['REQUEST_METHOD'] = $value;
    }
    
    /**
     * check if request method is GET
     *
     * @return bool
     */
    public function isGet(): bool
    {
        return $this->method() === 'GET';
    }
    
    /**
     * check if request method is POST
     *
     * @return bool
     */
    public function isPost(): bool
    {
        return $this->method() === 'POST';
    }

    /**
     * retrieves full uri
     *
     * @return string
     */
    public function fullUri(): string
    {
        $uri = $this->headers('REQUEST_URI');
        $uri = str_replace('/' . config('app.folder'), '', $uri); //remove app folder if exists
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
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, strpos($uri, '/'), strpos($uri, '?'));
        }
 
        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        //return sanitized url
        return filter_var($uri, FILTER_SANITIZE_URL) === false ? $uri : filter_var($uri, FILTER_SANITIZE_URL);
    }
    
    /**
     * retrieves ip address
     *
     * @return string
     */
    public function remoteIP(): string
    {
        return $this->headers('REMOTE_ADDR');
    }
    
    /**
     * check if request item exists
     *
     * @param  string $item
     * @return bool
     */
    public function has(string $item): bool
    {
        return isset($this->{$item});
    }
    
    /**
     * check if request item exists and not empty
     *
     * @param  string $item
     * @return bool
     */
    public function filled(string $item): bool
    {
        return $this->has($item) && !empty($this->{$item});
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
     * @param  string $value
     * @return void
     */
    public function set(string $item, string $value): void
    {
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
        return array_merge($this->inputs(), $this->queries());
    }
}
