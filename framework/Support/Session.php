<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Support;

/**
 * Manage session
 */
class Session
{    
    /**
     * create
     *
     * @param  mixed $name
     * @param  mixed $data
     * @return void
     */
    public static function create(string $name, $data): void
    {
        create_session($name, $data);
    }
    
    /**
     * get
     *
     * @param  mixed $name
     * @return void
     */
    public static function get(string $name)
    {
        return get_session($name);
    }
    
    /**
     * has
     *
     * @param  mixed $name
     * @return bool
     */
    public static function has(string $name): bool
    {
        return session_has($name);
    }
    
    /**
     * close
     *
     * @param  mixed $name
     * @return void
     */
    public static function close(string $name): void
    {
        close_session($name);
    }
    
    /**
     * setUser
     *
     * @param  mixed $data
     * @return void
     */
    public static function setUser($data): void
    {
        self::create(config('app.name') . '_user', $data);
    }
    
    /**
     * getUser
     *
     * @return void
     */
    public static function getUser()
    {
        return self::get(config('app.name') . '_user');
    }
    
    /**
     * hasUser
     *
     * @return bool
     */
    public static function hasUser(): bool
    {
        return self::has(config('app.name') . '_user');
    }
    
    /**
     * deleteUser
     *
     * @return void
     */
    public static function deleteUser(): void
    {
        self::close(config('app.name') . '_user');
    }
    
    /**
     * addBrowsingHistory
     *
     * @param  mixed $content
     * @return void
     */
    public static function addHistory($content): void
    {
        self::create('browsing_history', $content);
    }
    
    /**
     * getHistory
     *
     * @return void
     */
    public static function getHistory()
    {
        return self::get('browsing_history');
    }
    
    /**
     * clearHistory
     *
     * @return void
     */
    public static function clearHistory(): void
    {
        self::close('browsing_history');
    }
    
    /**
     * flash
     *
     * @param  mixed $title
     * @param  mixed $content
     * @return void
     */
    public static function flash(string $title, $content): void
    {
        self::create('flash_messages', [
			$title => $content
		]);
    }
}