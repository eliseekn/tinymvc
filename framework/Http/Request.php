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
     * @var string
     */
    protected $url;

    /**
     * response request
     *
     * @var string
     */
    protected $response = [];

    /**
     * $_FILES request
     *
     * @var array
     */
    protected $file = [];

    /**
     * instantiates class with url for request to send
     *
     * @param  string $url url to send request
     * @return void
     */
    public function __construct(string $url = '')
    {
        $this->url = $url;
    }

    /**
     * retrieves request headers
     *
     * @param  string $field name of $_SERVER array field
     * @return mixed returns field value or empty string
     */
    public function getHeaders(string $field = '')
    {
        if (!empty($this->url)) {
            return empty($field) ? $this->response['headers'] : $this->response['headers'][$field] ?? '';
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
    public function postQuery(string $field = '')
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
     * retrieves request file
     *
     * @param  string $field name of $_FILES array field
     * @return array returns array value
     */
    public function getFile(string $field)
    {
        $this->file = $_FILES[$field] ?? [];
        return $this;
    }

    /**
     * move uploaded file
     *
     * @param  string $destination file destination
     * @param  string|null $filename uploaded filename
     * @return bool returns true or false
     */
    public function moveTo(string $destination, ?string $filename = null): bool
    {
        $filename = is_null($filename) ? basename($this->file['name']) : $filename;
        return move_uploaded_file($this->file['tmp_name'], $destination . DIRECTORY_SEPARATOR . $filename);
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
     * @return mixed
     */
    public function getURI()
    {
        $uri = $this->getHeaders('REQUEST_URI');
        $uri = str_replace(ROOT_FOLDER, '', $uri); //remove root subfolder if exists 

        //looks for "?page=" or something
        if (strpos($uri, '?')) {
            $uri = substr($uri, strpos($uri, '/'), strpos($uri, '?'));
            
            if ($uri !== '/') {
                $uri = rtrim($uri, '/');
            }
        }

        return $uri;
    }

    /**
     * send request to url
     *
     * @param  string $method method name
     * @param  mixed $data data to send
     * @param  bool $json_data send data in json format (only for POST request)
     * @return mixed
     */
    public function sendRequest(string $method, ?array $data = null, bool $json_data = false)
    {
        $this->response = curl($method, [$this->url], $data, $json_data);
        return $this;
    }

    /**
     * get response body
     *
     * @return void
     */
    public function getBody()
    {
        return $this->response['body'];
    }
}
