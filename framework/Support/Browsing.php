<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Support;

/**
 * Manage browsing history
 */
class Browsing
{
    /**
     * addBrowsingHistory
     *
     * @param  mixed $content
     * @return void
     */
    public static function addHistory($content): void
    {
        Session::create(config('app.name') . '_history', $content);
    }
    
    /**
     * getHistory
     *
     * @return void
     */
    public static function getHistory()
    {
        return Session::get(config('app.name') . '_history');
    }
    
    /**
     * clearHistory
     *
     * @return void
     */
    public static function clearHistory(): void
    {
        Session::close(config('app.name') . '_history');
    }
}