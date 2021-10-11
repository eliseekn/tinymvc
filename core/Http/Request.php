<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http;

use Core\Support\Uploader;
use Core\Http\Validator\GUMPValidator as Validator;

/**
 * Handle HTTP requests
 */
class Request
{    
    public function __construct()
    {
        foreach ($this->all() as $key => $value) {
            $this->{$key} = $value;
        }
    }

    public function headers(?string $key = null, $default = null)
    {
        $header = is_null($key) ? $_SERVER : ($_SERVER[$key] ?? '');
        return empty($header) || is_null($header) ? $default : $header;
    }
    
    public function getHttpAuth()
    {
        $header = $this->headers('HTTP_AUTHORIZATION', '');
        return empty($header) ? [] : explode(' ', $header);
    }
    
    public function host()
    {
        return $this->headers('HTTP_HOST', '');
    }
    
    public function queries(?string $key = null, $default = null)
    {
        $query = is_null($key) ? $_GET : ($_GET[$key] ?? '');
        return empty($query) || is_null($query) ? $default : $query;
    }

    public function inputs(?string $key = null, $default = null)
    {
        $_POST = array_merge($_POST, $this->rawData());

        $input = is_null($key) ? $_POST : ($_POST[$key] ?? '');
        return empty($input) || is_null($input) ? $default : $input;
    }

    public function files(?string $key = null, $default = null)
    {
        $files = is_null($key) ? $_FILES : ($_FILES[$key] ?? '');
        return empty($files) || is_null($files) ? $default : $files;
    }

    public function rawData()
    {
        $data = [];
        parse_raw_http_request($data);

        return $data;
    }

    private function getSingleFile(string $input, array $allowed_extensions = [])
    {
        if (!isset($_FILES[$input]) || empty($_FILES[$input])) return null;
        
        return new Uploader([
            'name' => $_FILES[$input]['name'],
            'tmp_name' => $_FILES[$input]['tmp_name'],
            'size' => $_FILES[$input]['size'],
            'type' => $_FILES[$input]['type'],
            'error' => $_FILES[$input]['error']
        ], $allowed_extensions);
    }

    private function getMultipleFiles(string $input, array $allowed_extensions = [])
    {
        $files = [];

        if (!isset($_FILES[$input]) || empty($_FILES[$input])) return $files;

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
    
    public function getFiles(string $input, bool $multiple = false, array $allowed_extensions = [])
    {
        return $multiple ? $this->getMultipleFiles($input, $allowed_extensions) 
            : $this->getSingleFile($input, $allowed_extensions);
    }

    public function method(?string $value = null)
    {
        return is_null($value) ? $this->headers('REQUEST_METHOD', '') : $_SERVER['REQUEST_METHOD'] = $value;
    }
    
    public function is(string $method)
    {
        return $this->method() === strtoupper($method);
    }

    public function fullUri()
    {
        $uri = $this->headers('REQUEST_URI', '');
        $uri = urldecode($uri);
        $uri = filter_var($uri, FILTER_SANITIZE_URL) === false ? $uri : filter_var($uri, FILTER_SANITIZE_URL);
        $uri = str_replace('//', '/', $uri);
 
        if ($uri !== '/') $uri = rtrim($uri, '/');

        return $uri;
    }

    public function uri()
    {
        $uri = $this->fullUri();

        //removes queries from uri
        if (strpos($uri, '?')) {
            $uri = substr($uri, strpos($uri, '/'), strpos($uri, '?'));
        }

        return $uri;        
    }

    public function uriContains(string $uri)
    {
        return url_contains($uri);
    }
    
    public function remoteIP()
    {
        return $this->headers('REMOTE_ADDR', '');
    }
    
    public function has(string ...$items)
    {
        $result = false;

        foreach ($items as $item) {
            $result = isset($this->{$item});
        }

        return $result;
    }
    
    public function hasQuery(string ...$items)
    {
        $result = false;

        foreach ($items as $item) {
            $result = !is_null($this->queries($item));
        }

        return $result;
    }
    
    public function hasInput(string ...$items)
    {
        $result = false;

        foreach ($items as $item) {
            $result = !is_null($this->inputs($item));
        }

        return $result;
    }
    
    public function filled(string ...$items)
    {
        $result = $this->has(...$items);

        if (!$result) return false;

        foreach ($items as $item) {
            $result = !empty($this->{$item}) && !is_null($this->{$item});
        }

        return $result;
    }

    /**
     * Retrieves POST/GET item value
     */
    public function get(string $item, $default = null)
    {
        return $this->filled($item) ? $this->{$item} : $default;
    }

    /**
     * Set POST/GET item value
     */
    public function set(string $item, $value)
    {
        if (isset($_POST[$item])) $_POST[$item] = $value;
        if (isset($_GET[$item])) $_GET[$item] = $value;

        $this->{$item} = $value;
    }
    
    public function only(string ...$items)
    {
        $result = [];

        foreach ($items as $item) {
            if ($this->has($item)) {
                $result = array_merge($result, [$item => $this->{$item}]);
            }
        }

        return $result;
    }
    
    public function except(string ...$items)
    {
        $result = [];

        if (empty($this->all())) return $result;

        foreach ($items as $item) {
            foreach ($this->all() as $key => $input) {
                if ($item !== $key) {
                    $result = array_merge($result, [$key => $input]);
                }
            }
        }

        return $result;
    }
    
    public function all()
    {
        $all = [];

        if (!is_null($this->inputs())) {
            $all = array_merge($all, $this->inputs());
        }

        if (!is_null($this->queries())) {
            $all = array_merge($all, $this->queries());
        }

        if (!is_null($this->files())) {
            $all = array_merge($all, $this->files());
        }

        return $all;
    }

    public function validate(array $rules = [], array $messages = [], array $inputs = [])
    {
        if (empty($inputs)) {
            $inputs = $this->inputs();
        } else {
            $inputs = $this->only(...$inputs);
        }

        return Validator::validate($inputs, $rules, $messages);
    }
}
