<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Support;

use Framework\HTTP\Request;

/**
 * Manage browsing history
 */
class Browsing
{
    /**
     * add url to browsing history
     *
     * @return void
     */
    public static function set(): void
    {
        $url = self::get();

        if (empty($url)) {
            $url = [Request::getFullUri()];
        } else {
            $url[] = Request::getFullUri();
        }

        Session::create(config('app.name') . '_history', $url);
    }
    
    /**
     * get browsing history
     *
     * @return mixed
     */
    public static function get()
    {
        return Session::get(config('app.name') . '_history');
    }
    
    /**
     * clear browsing history
     *
     * @return void
     */
    public static function clear(): void
    {
        Session::close(config('app.name') . '_history');
    }
}