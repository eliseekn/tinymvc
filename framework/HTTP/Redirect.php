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

namespace Framework\HTTP;

use Exception;
use Framework\Routing\Route;

/**
 * Redirect
 * 
 * Redirection handler
 */
class Redirect
{
    /**
     * url to redirect
     *
     * @var string
     */
    protected static $redirect_url = '';
    
    /**
     * redirect to url 
     *
     * @param  string $url url to redirect to
     * @return mixed
     */
    public static function toUrl(string $url)
    {
        self::$redirect_url = $url;
        return new self();
    }

    /**
     * redirect to route
     *
     * @param  string $name route name
     * @param  array $params parameters 
     * @return mixed
     */
    public static function toRoute(string $name, array $params = [])
    {
        $params = empty($params) ? '' : implode('/', $params);

        //search key from value in a multidimensional array
        //https://www.php.net/manual/en/function.array-search.php
        $url = array_search(
            $name,
            array_map(
                function ($val) {
                    return $val['name'];
                },
                Route::$routes
            )
        );

        if (empty($url)) {
            throw new Exception('Route "' . $name . '" not found.');
        }

        self::$redirect_url = empty($params) ? $url : $url . '/' . $params;
        return new self();
    }

    /**
     * redirect to handler
     *
     * @param  string $name handler name (ex: Controller@action)
     * @param  array $params parameters 
     * @return mixed
     */
    public static function toHandler(string $name, array $params = [])
    {
        $params = empty($params) ? '' : implode('/', $params);

        //search key from value in a multidimensional array
        //https://www.php.net/manual/en/function.array-search.php
        $url = array_search(
            $name,
            array_map(
                function ($val) {
                    return $val['handler'];
                },
                Route::$routes
            )
        );

        if (empty($url)) {
            throw new Exception('Handler "' . $name . '" not found.');
        }

        self::$redirect_url = empty($params) ? $url : $url . '/' . $params;
        return new self();
    }

    /**
     * go to previous page
     *
     * @return mixed
     */
    public static function back()
    {
        $browsing_history = get_session('browsing_history');

        if (!empty($browsing_history)) {
            $current_url = end($browsing_history);
            $key = array_search($current_url, $browsing_history); 
            self::$redirect_url = $browsing_history[$key - 1];
        }

        return new self();
    }

    /**
     * refresh page
     *
     * @return mixed
     */
    public static function refresh()
    {
        $browsing_history = get_session('browsing_history');

        if (!empty($browsing_history)) {
            self::$redirect_url = end($browsing_history);
        }

        return new self();
    }

    /**
     * redirects with session flash message
     *
     * @param  string $title title of message
     * @param  mixed $content content of message
     * @return void
     */
    public function withMessage(string $title, $content): void
    {
        create_flash_message($title, $content);
        redirect_to(self::$redirect_url);
    }

    /**
     * redirects with error flash message
     *
     * @param  mixed $content content of message
     * @return void
     */
    public function withError($content): void
    {
        self::withMessage('error', $content);
    }

    /**
     * redirects with success flash message
     *
     * @param  mixed $content content of message
     * @return void
     */
    public function withSuccess($content): void
    {
        self::withMessage('success', $content);
    }

    /**
     * redirects only
     *
     * @return void
     */
    public function only(): void
    {
        redirect_to(self::$redirect_url);
    }
}
