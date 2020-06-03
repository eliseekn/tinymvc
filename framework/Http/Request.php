<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Http;

use Framework\Support\Uploader;

/**
 * Requests
 * 
 * Handle HTTP requests
 */
class Request
{
    /**
     * url to send request
     *
     * @var array
     */
    protected static $urls = [];

    /**
     * response request
     *
     * @var string
     */
    protected static $response = [];

    /**
     * retrieves request headers
     *
     * @param  string $field name of $_SERVER array field
     * @return mixed returns field value or empty string
     */
    public function getHeaders(string $field = '')
    {
        if (!empty(self::$urls)) {
            return empty($field) ? self::$response['headers'] : self::$response['headers'][$field] ?? '';
        } else {
            return empty($field) ? $_SERVER : $_SERVER[$field] ?? '';
        }
    }

    /**
     * retrieves request get method
     *
     * @param  string $field name of $_GET array field
     * @return mixed returns field value or array or empty string
     */
    public function getQuery(string $field = '')
    {
        return empty($field) ? $_GET : $_GET[$field] ?? '';
    }

    /**
     * retrieves request post method
     *
     * @param  string $field name of $_POST array field
     * @return mixed returns field value or array or empty string
     */
    public function getInput(string $field = '')
    {
        return empty($field) ? $_POST : $_POST[$field] ?? '';
    }

    /**
     * retrieves raw data from requests
     *
     * @return mixed data content or false
     */
    public function getRawData()
    {
        return file_get_contents('php://input');
    }

    /**
     * retrieves $_FILES request
     *
     * @param  string $field name of $_FILES array field
     * @return array returns array of uploader class instance
     */
    public function getFile(string $field): array
    {
        $uploaders = [];

        if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
            $files_count = is_array($_FILES[$field]['tmp_name']) ? count($_FILES[$field]['tmp_name']) : 1;

            for ($i = 0; $i < $files_count; $i ++) {
                $uploaders[] = new Uploader([
                    'name' => $_FILES[$field]['name'][$i],
                    'tmp_name' => $_FILES[$field]['tmp_name'][$i],
                    'size' => $_FILES[$field]['size'][$i],
                    'type' => $_FILES[$field]['type'][$i]
                ]);
            }
        }
        
        return $uploaders;
    }

    /**
     * retrieves request method
     *
     * @return mixed
     */
    public function getMethod()
    {
        return $this->getHeaders('REQUEST_METHOD');
    }

    /**
     * retrieves uri request
     *
     * @return string
     */
    public function getURI(): string
    {
        $uri = $this->getHeaders('REQUEST_URI');
        $uri = str_replace(ROOT_FOLDER, '', $uri); //remove root subfolder if exists 

        //looks for "?page=" or something
        if (strpos($uri, '?') !== false) {
            $uri = substr($uri, strpos($uri, '/'), strpos($uri, '?'));
            
            if ($uri !== '/') {
                $uri = rtrim($uri, '/');
            }
        }

        //return sanitized url
        return filter_var($uri, FILTER_SANITIZE_URL) ===  false ? $uri : filter_var($uri, FILTER_SANITIZE_URL);
    }
    
    /**
     * set $_POST query
     *
     * @param  string $field name of field
     * @param  mixed $value
     * @return void
     */
    public function setInput(string $field, $value): void
    {
        $_POST[$field] = $value;
    }
    
    /**
     * set $_GET query
     *
     * @param  string $field name of field
     * @param  mixed $value
     * @return void
     */
    public function setQuery(string $field, $value): void
    {
        $_GET[$field] = $value;
    }

    /**
     * send request to url
     *
     * @param  string $method method name
     * @param  mixed $data data to send
     * @param  bool $json_data send data in json format (only for POST request)
     * @return mixed
     */
    public static function send(string $method, array $urls, $headers = [], ?array $data = null, bool $json_data = false)
    {
        self::$urls = $urls;
        self::$response = curl($method, self::$urls, $headers, $data, $json_data);
        return new self();
    }

    /**
     * get response body
     *
     * @return mixed
     */
    public function getBody()
    {
        return self::$response['body'];
    }
}
