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

use Framework\Core\View;
use Framework\Core\Route;
use Framework\Exceptions\RouteNotFoundException;

/**
 * Redirect
 * 
 * Redirection class
 */
class Redirect
{
    /**
     * url to redirect
     *
     * @var string
     */
    private static $redirect_url = '';

    /**
     * redirect to route
     *
     * @param  string $name route name or Controller@action
     * @param  array $params parameters 
     * @return void
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
            $url = array_search(
                $name,
                array_map(
                    function ($val) {
                        return $val['controller'];
                    },
                    Route::$routes
                )
            );

            if (empty($url)) {
                throw new RouteNotFoundException($name);
            }
        }

        self::$redirect_url = empty($params) ? $url : $url . '/' . $params;
        return new self();
    }

    /**
     * go to previous page
     *
     * @return void
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
     * @return void
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
     * redirects only
     *
     * @return void
     */
    public function only(): void
    {
        redirect_to(self::$redirect_url);
    }
}
