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
        Session::create('browsing_history', $content);
    }
    
    /**
     * getHistory
     *
     * @return void
     */
    public static function getHistory()
    {
        return Session::get('browsing_history');
    }
    
    /**
     * clearHistory
     *
     * @return void
     */
    public static function clearHistory(): void
    {
        Session::close('browsing_history');
    }
}