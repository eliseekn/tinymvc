<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Http;

use Framework\Support\Uploader;
use Framework\Support\Validator;

/**
 * Handle HTTP requests
 */
class Request
{
    public function __construct()
    {
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
     * retrieves headers
     *
     * @param  string|null $key
     * @return array
     */
    public function headers(?string $key = null): array
    {
        return is_null($key) ? self::getHeaders() : self::getHeader($key);
    }
        
    /**
     * retrieves method
     *
     * @return string
     */
    public function method(): string
    {
        return self::getMethod();
    }
    
    /**
     * retrieves uri
     *
     * @param  bool $full
     * @return string
     */
    public function uri(bool $full = false): string
    {
        return $full ? self::getFullUri() : self::getUri();
    }

    /**
     * retrieves POST inputs
     *
     * @param  string|null $input
     * @return array
     */
    public function inputs(?string $input = null): array
    {
        return is_null($input) ? self::getInputs() : self::getInput($input);
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
     * retrieves GET queries
     *
     * @param  string|null $key
     * @return array
     */
    public function queries(?string $key = null): array
    {
        return  is_null($key) ? self::getQueries() : self::getQuery($key);
    }
    
    /**
     * set GET query
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
     * retrieves raw data
     *
     * @return mixed
     */
    public function rawData()
    {
        return self::getRawData();
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
        return $mutliple ? self::getFiles($input, $allowed_extensions) : self::getFile($input, $allowed_extensions);
    }
    
    /**
     * retrieves remote IP
     *
     * @return string
     */
    public function remoteIP(): string
    {
        return self::getRemoteIP();
    }
    
    /**
     * check if request has POST or GET item
     *
     * @param  string $item
     * @return bool
     */
    public function has(string $item): bool
    {
        return isset($this->{$item}) && !empty($this->{$item});
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
            foreach ($this->inputs() as $key => $input) {
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

    /**
     * validate inputs and redirect with flash session data
     *
     * @param  array|null $inputs
     * @param  array $rules
     * @param  array $messages
     * @return void
     */
    public function validate(?array $inputs = null, array $rules = [], array $messages = []): void
    {
        $inputs = is_null($inputs) ? $this->inputs() : $inputs;
        $validator = Validator::validate($inputs, $rules, $messages);

        if ($validator->fails()) {
            Redirect::back()->withErrors($validator->errors())->withInputs($validator->inputs());
        }
    }
}
