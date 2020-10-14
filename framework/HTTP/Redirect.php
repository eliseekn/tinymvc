<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\HTTP;

use Exception;
use Framework\Routing\Route;
use Framework\Support\Browsing;
use Framework\Support\Notification;

/**
 * Handle HTTP redirection
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
     * @param  string $url
     * @param  array $params
     * @return \Framework\HTTP\Redirect
     */
    public static function toUrl(string $url, array $params = []): self
    {
        $params = empty($params) ? '' : implode('/', $params);
        self::$redirect_url = empty($params) ? $url : $url . '/' . $params;
        return new self();
    }

    /**
     * redirect to route
     *
     * @param  string $name
     * @param  array $params
     * @return \Framework\HTTP\Redirect
     */
    public static function toRoute(string $name, array $params = []): self
    {
        self::$redirect_url = route_url($name, $params);
        return new self();
    }

    /**
     * redirect to handler
     *
     * @param  string $handler
     * @param  array $params
     * @return \Framework\HTTP\Redirect
     */
    public static function toHandler(string $handler, array $params = []): self
    {
        $params = empty($params) ? '' : implode('/', $params);

        //search key from value in a multidimensional array
        //https://www.php.net/manual/en/function.array-search.php
        $url = array_search(
            $handler,
            array_map(
                function ($val) {
                    return $val['handler'];
                },
                Route::$routes
            )
        );

        if (empty($url)) {
            throw new Exception('Handler "' . $handler . '" not found.');
        }

        self::$redirect_url = empty($params) ? $url : $url . '/' . $params;
        return new self();
    }

    /**
     * go to previous page
     *
     * @return \Framework\HTTP\Redirect
     */
    public static function back(): self
    {
        $browsing_history = Browsing::getHistory();

        if (!empty($browsing_history)) {
            end($browsing_history);
            self::$redirect_url = prev($browsing_history);
        }

        return new self();
    }

    /**
     * redirects with success flash messages
     *
     * @param  mixed $messages
     * @return void
     */
    public function withSuccess($messages): void
    {
        Notification::alert($messages)->success();
        redirect_to(self::$redirect_url);
    }

    /**
     * redirects with error flash messages
     *
     * @param  mixed $messages
     * @return void
     */
    public function withError($messages): void
    {
        Notification::alert($messages)->error();
        redirect_to(self::$redirect_url);
    }

    /**
     * redirects with warning flash messages
     *
     * @param  mixed $messages
     * @return void
     */
    public function withWarning($messages): void
    {
        Notification::alert($messages)->warning();
        redirect_to(self::$redirect_url);
    }

    /**
     * redirects with info flash messages
     *
     * @param  mixed $messages
     * @return void
     */
    public function withInfo($messages): void
    {
        Notification::alert($messages)->info();
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
