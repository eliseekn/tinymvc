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
     * retrieves request headers
     *
     * @param  string $field name of $_SERVER array field
     * @return mixed returns field value or empty string
     */
    public static function getHeaders(string $field = '')
    {
        return empty($field) ? $_SERVER : $_SERVER[$field] ?? '';
    }

    /**
     * retrieves request get method
     *
     * @param  string $field name of $_GET array field
     * @return mixed returns field value or array or empty string
     */
    public static function getQuery(string $field = '')
    {
        return empty($field) ? $_GET : $_GET[$field] ?? '';
    }

    /**
     * retrieves request post method
     *
     * @param  string $field name of $_POST array field
     * @return mixed returns field value or array or empty string
     */
    public static function getField(string $field = '')
    {
        return empty($field) ? $_POST : $_POST[$field] ?? '';
    }

    /**
     * retrieves raw data from requests
     *
     * @return mixed data content or false
     */
    public static function getRawData()
    {
        return file_get_contents('php://input');
    }

    /**
     * retrieves $_FILES request
     *
     * @param  string $field name of $_FILES array field
     * @return array returns array of uploader class instance
     */
    public static function getFile(string $field): array
    {
        $files = [];

        if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
            $count = is_array($_FILES[$field]['tmp_name']) ? count($_FILES[$field]['tmp_name']) : 1;

            for ($i = 0; $i < $count; $i++) {
                $files[] = new Uploader([
                    'name' => $_FILES[$field]['name'][$i],
                    'tmp_name' => $_FILES[$field]['tmp_name'][$i],
                    'size' => $_FILES[$field]['size'][$i],
                    'type' => $_FILES[$field]['type'][$i]
                ]);
            }
        }
        
        return $files;
    }

    /**
     * retrieves request method
     *
     * @return mixed
     */
    public static function getMethod()
    {
        return self::getHeaders('REQUEST_METHOD');
    }

    /**
     * retrieves requested uri
     *
     * @return string
     */
    public static function getURI(): string
    {
        $uri = self::getHeaders('REQUEST_URI');
        $uri = str_replace(ROOT_FOLDER, '', $uri); //remove root subfolder if exists 

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
     * set $_POST query
     *
     * @param  string $field name of field
     * @param  mixed $value
     * @return void
     */
    public static function setField(string $field, $value): void
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
    public static function setQuery(string $field, $value): void
    {
        $_GET[$field] = $value;
    }
}
