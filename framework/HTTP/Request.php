<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\HTTP;

use Framework\Support\Uploader;

/**
 * Handle HTTP requests
 */
class Request
{
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
     * @param  string $field
     * @return string returns header value
     */
    public static function getHeader(string $field): string
    {
        return self::getHeaders()[$field] ?? '';
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
     * @param  string $field
     * @return string returns query value
     */
    public static function getQuery(string $field): string
    {
        return self::getQueries()[$field] ?? '';
    }
    
    /**
     * set query value
     *
     * @param  string $query
     * @param  mixed $value
     * @return void
     */
    public static function setQuery(string $query, $value): void
    {
        $_GET[$query] = $value;
    }

    /**
     * retrieves post fields
     *
     * @param  string $field
     * @return array returns post fields
     */
    public static function getFields(): array
    {
        return $_POST;
    }

    /**
     * retrieves single post field
     *
     * @param  string $field
     * @return mixed returns field value
     */
    public static function getField(string $field): string
    {
        return self::getFields()[$field] ?? '';
    }
    
    /**
     * set field value
     *
     * @param  string $field
     * @param  mixed $value
     * @return void
     */
    public static function setField(string $field, $value): void
    {
        $_POST[$field] = $value;
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
     * @param  string $field
     * @param  array $allowed_extensions
     * @return mixed returns uploader instance
     */
    public static function getFile(string $field, array $allowed_extensions = []): Uploader
    {
        return new Uploader([
            'name' => $_FILES[$field]['name'],
            'tmp_name' => $_FILES[$field]['tmp_name'],
            'size' => $_FILES[$field]['size'],
            'type' => $_FILES[$field]['type'],
            'error' => $_FILES[$field]['error']
        ], $allowed_extensions);
    }

    /**
     * retrieves multiple files
     *
     * @param  string $field
     * @param  array $allowed_extensions
     * @return array returns array of uploader instance
     */
    public static function getFiles(string $field, array $allowed_extensions = []): array
    {
        $files = [];

        if (isset($_FILES[$field]) && !empty($_FILES[$field])) {
            $count = is_array($_FILES[$field]['tmp_name']) ? count($_FILES[$field]['tmp_name']) : 1;

            for ($i = 0; $i < $count; $i++) {
                $files[] = new Uploader([
                    'name' => $_FILES[$field]['name'][$i],
                    'tmp_name' => $_FILES[$field]['tmp_name'][$i],
                    'size' => $_FILES[$field]['size'][$i],
                    'type' => $_FILES[$field]['type'][$i],
                    'error' => $_FILES[$field]['error'][$i]
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
    public static function getFullURI(): string
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
    public static function getURI(): string
    {
        $uri = self::getFullURI();

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
}
