<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\HTTP;

use Framework\Support\Alert;
use Framework\Support\Session;

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
     * @param  array|string $params
     * @return \Framework\HTTP\Redirect
     */
    public static function toUrl(string $url, $params = null): self
    {
        $params = is_array($params) ? (empty($params) ? '' : implode('/', $params)) : $params;
        self::$redirect_url = is_null($params) ? $url : $url . '/' . $params;
        return new self();
    }

    /**
     * redirect to route
     *
     * @param  string $name
     * @param  array|string $params
     * @return \Framework\HTTP\Redirect
     */
    public static function toRoute(string $name, $params = null): self
    {
        self::$redirect_url = route_url($name, $params);
        return new self();
    }

    /**
     * go to previous page
     *
     * @return \Framework\HTTP\Redirect
     */
    public static function back(): self
    {
        $history = Session::getHistory();

        if (!empty($history)) {
            end($history);
            self::$redirect_url = prev($history);
        }

        return new self();
    }

    /**
     * redirects with success flash messages
     *
     * @param  mixed $messages
     * @param  string $title
     * @return void
     */
    public function withSuccess($messages, string $title = ''): void
    {
        Alert::default($messages)->success($title);
        redirect_to(self::$redirect_url);
    }

    /**
     * redirects with error flash messages
     *
     * @param  mixed $messages
     * @param  string $title
     * @return void
     */
    public function withError($messages, string $title = ''): void
    {
        Alert::default($messages)->error($title);
        redirect_to(self::$redirect_url);
    }

    /**
     * redirects with warning flash messages
     *
     * @param  mixed $messages
     * @param  string $title
     * @return void
     */
    public function withWarning($messages, string $title = ''): void
    {
        Alert::default($messages)->warning($title);
        redirect_to(self::$redirect_url);
    }

    /**
     * redirects with info flash messages
     *
     * @param  mixed $messages
     * @param  string $title
     * @return void
     */
    public function withInfo($messages, string $title = ''): void
    {
        Alert::default($messages)->info($title);
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
