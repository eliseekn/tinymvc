<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\HTTP;

use Framework\Support\Uploader;

/**
 * Handle HTTP requests
 */
class Request
{
    public function __construct()
    {
        //add headers as properties
        foreach (self::getHeaders() as $key => $value) {
            $this->{$key} = $value;
        }

        //add fields as properties
        foreach (self::getInputs() as $key => $value) {
            $this->{$key} = $value;
        }

        //add queries as properties
        foreach (self::getQueries() as $key => $value) {
            $this->{$key} = $value;
        }
    }

    /**
     * retrieves headers
     *
     * @return array returns headers
     */
    public static function getHeaders(): array
    {
        return $_SERVER;
    }
    
    /**
     * retrieves single header value
     *
     * @param  string $key
     * @return string returns header value
     */
    public static function getHeader(string $key): string
    {
        return self::getHeaders()[$key] ?? '';
    }

    /**
     * retrieves queries
     *
     * @return array returns queries
     */
    public static function getQueries(): array
    {
        return $_GET;
    }

    /**
     * retrieves single query value
     *
     * @param  string $key
     * @return string returns query value
     */
    public static function getQuery(string $key): string
    {
        return self::getQueries()[$key] ?? '';
    }

    /**
     * check if get query exists
     *
     * @param  string $key
     * @return bool
     */
    public static function hasQuery(string $key) : bool
    {
        return !empty(self::getQuery($key));
    }
    
    /**
     * set query value
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public static function setQuery(string $ey, $value): void
    {
        $_GET[$ey] = $value;
    }

    /**
     * retrieves post inputs
     *
     * @return array returns post inputs
     */
    public static function getInputs(): array
    {
        return $_POST;
    }

    /**
     * retrieves single post input
     *
     * @param  string $input
     * @return mixed returns input value
     */
    public static function getInput(string $input) 
    {
        return self::getInputs()[$input] ?? '';
    }

    /**
     * check if post input exists
     *
     * @param  string $input
     * @return bool
     */
    public static function hasInput(string $input) : bool
    {
        return !empty(self::getInput($input));
    }
    
    /**
     * set input value
     *
     * @param  string $input
     * @param  mixed $value
     * @return void
     */
    public static function setInput(string $input, $value): void
    {
        $_POST[$input] = $value;
    }

    /**
     * retrieves raw data
     *
     * @return mixed
     */
    public static function getRawData()
    {
        return file_get_contents('php://input');
    }

    /**
     * retrieves single file
     *
     * @param  string $input
     * @param  array $allowed_extensions
     * @return \Framework\Support\Uploader returns uploader instance
     */
    public static function getFile(string $input, array $allowed_extensions = []): Uploader
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
     * @return array returns array of uploader instance
     */
    public static function getFiles(string $input, array $allowed_extensions = []): array
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
     * retrieves request method
     *
     * @return string
     */
    public static function getMethod(): string
    {
        return self::getHeader('REQUEST_METHOD');
    }

    /**
     * retrieves full uri
     *
     * @return string
     */
    public static function getFullUri(): string
    {
        $uri = self::getHeader('REQUEST_URI');
        $uri = str_replace(config('app.folder'), '', $uri); //remove app folder if exists 
        return $uri;
    }

    /**
     * retrieves partial uri
     *
     * @return string
     */
    public static function getUri(): string
    {
        $uri = self::getFullUri();

        //looks for "?page=" or something like and remove it from uri
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, strpos($uri, '/'), strpos($uri, '?'));
            
            if ($uri !== '/') {
                $uri = rtrim($uri, '/');
            }
        }

        //return sanitized url
        return filter_var($uri, FILTER_SANITIZE_URL) === false ? $uri : filter_var($uri, FILTER_SANITIZE_URL);
    }
    
    /**
     * retrieves ip address
     *
     * @return string
     */
    public static function getRemoteIP(): string
    {
        return self::getHeader('REMOTE_ADDR');
    }
    
    /**
     * files
     *
     * @param  string $input
     * @param  array $allowed_extensions
     * @param  bool $mutliple
     * @return \Framework\Support\Uploader|array
     */
    public function files(string $input, array $allowed_extensions = [], bool $mutliple = false)
    {
        return $mutliple ? self::getFiles($input, $allowed_extensions) : self::getFile($input, $allowed_extensions);
    }
    
    /**
     * headers
     *
     * @return array
     */
    public function headers(): array
    {
        return self::getHeaders();
    }
        
    /**
     * method
     *
     * @return string
     */
    public function method(): string
    {
        return self::getMethod();
    }
    
    /**
     * fullUri
     *
     * @return string
     */
    public function fullUri(): string
    {
        return self::getFullUri();
    }
    
    /**
     * uri
     *
     * @return string
     */
    public function uri(): string
    {
        return self::getUri();
    }

    /**
     * inputs
     *
     * @return array
     */
    public function inputs(): array
    {
        return self::getInputs();
    }
    
    /**
     * set POST input
     *
     * @param  string $input
     * @param  mixed $value
     * @return void
     */
    public function input(string $input, $value): void
    {
        self::setInput($input, $value);
    }
    
    /**
     * queries
     *
     * @return array
     */
    public function queries(): array
    {
        return self::getQueries();
    }
    
    /**
     * query
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    public function query(string $key, $value): void
    {
        self::setQuery($key, $value);
    }
    
    /**
     * raw
     *
     * @return mixed
     */
    public function raw()
    {
        return self::getRawData();
    }
    
    /**
     * has
     *
     * @param  string $item
     * @return bool
     */
    public function has(string $item): bool
    {
        return isset($this->{$item});
    }
    
    /**
     * only
     *
     * @param  string[] $items
     * @return mixed
     */
    public function only(string ...$items)
    {
        $result = [];

        foreach ($items as $item) {
            if ($this->has($item)) {
                $result = array_merge($result, [
                    $item => $this->{$item}
                ]);
            }
        }

        return (object) $result;
    }
    
    /**
     * except
     *
     * @param  string[] $items
     * @return mixed
     */
    public function except(string ...$items)
    {
        $result = [];

        foreach ($items as $item) {
            foreach ($this->inputs() as $key => $input) {
                if ($item !== $key) {
                    $result = array_merge($result, [
                        $key => $input
                    ]);
                }
            }
        }

        return (object) $result;
    }
}
