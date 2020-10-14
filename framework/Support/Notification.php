<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Support;

/**
 * Manage flash notifications
 */
class Notification
{
    /**
     * @var array
     */
    protected static $flash = [];

    /**
     * alert
     *
     * @param  array|string $messages
     * @param  mixed $dissmiss
     * @return \Framework\Support\Notification
     */
    public static function alert($messages, bool $dissmiss = true): self
    {
        self::$flash = [
            'messages' => $messages,
            'display' => 'default',
            'dismiss' => $dissmiss
        ];

        return new self();
    }
    
    /**
     * popup
     *
     * @param  array|string $messages
     * @return \Framework\Support\Notification
     */
    public function popup($messages): self
    {
        self::$flash = [
            'messages' => $messages,
            'display' => 'popup'
        ];

        return new self();
    }

    /**
     * toast
     *
     * @param  array|string $messages
     * @return \Framework\Support\Notification
     */
    public static function toast($messages) : self
    {
        self::$flash = [
            'messages' => $messages,
            'display' => 'toast'
        ];

        return new self();
    }
    
    /**
     * success
     *
     * @return void
     */
    public function success(): void
    {
        self::$flash = array_merge(self::$flash, ['type' => 'success']);
        Session::create('flash_messages', self::$flash);
    }
    
    /**
     * error
     *
     * @return void
     */
    public function error(): void
    {
        self::$flash = array_merge(self::$flash, ['type' => 'danger']);
        Session::create('flash_messages', self::$flash);
    }
    
    /**
     * info
     *
     * @return void
     */
    public function info(): void 
    {
        self::$flash = array_merge(self::$flash, ['type' => 'primary']);
        Session::create('flash_messages', self::$flash);
    }
    
    /**
     * warning
     *
     * @return void
     */
    public function warning(): void 
    {
        self::$flash = array_merge(self::$flash, ['type' => 'warning']);
        Session::create('flash_messages', self::$flash);
    }
}