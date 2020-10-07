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
     * @var array
     */
    protected static $flash = [];

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
     * init flash
     *
     * @param  array|string $messages
     * @return \Framework\Support\Session
     */
    public static function flash($messages): self
    {
        self::$flash = ['messages' => $messages];
        return new self();
    }
    
    /**
     * success
     *
     * @return self
     */
    public function success(): self
    {
        self::$flash = array_merge(self::$flash, ['type' => 'success']);
        return new self();
    }
    
    /**
     * error
     *
     * @return self
     */
    public function error(): self
    {
        self::$flash = array_merge(self::$flash, ['type' => 'danger']);
        return new self();
    }
    
    /**
     * info
     *
     * @return self
     */
    public function info(): self 
    {
        self::$flash = array_merge(self::$flash, ['type' => 'primary']);
        return new self();
    }
    
    /**
     * warning
     *
     * @return self
     */
    public function warning(): self 
    {
        self::$flash = array_merge(self::$flash, ['type' => 'warning']);
        return new self();
    }
    
    /**
     * default
     *
     * @param  mixed $dissmiss
     * @return void
     */
    public function default(bool $dissmiss = true): void
    {
        self::$flash = array_merge(self::$flash, [
            'display' => 'default',
            'dismiss' => $dissmiss
        ]);

        self::create('flash_messages', self::$flash);
    }
    
    /**
     * popup
     *
     * @return void
     */
    public function popup(): void
    {
        self::$flash = array_merge(self::$flash, ['display' => 'popup']);
        self::create('flash_messages', self::$flash);
    }
    
    /**
     * toast
     *
     * @return void
     */
    public function toast(): void
    {
        self::$flash = array_merge(self::$flash, ['display' => 'toast']);
        self::create('flash_messages', self::$flash);
    }
}