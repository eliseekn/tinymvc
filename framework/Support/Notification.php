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
     * @param  string $title
     * @param  mixed $dissmiss
     * @return \Framework\Support\Notification
     */
    public static function alert($messages, string $title = '', bool $dissmiss = true): self
    {
        self::$flash = [
            'title' => $title,
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
     * @param  string $title
     * @return \Framework\Support\Notification
     */
    public static function popup($messages, string $title = ''): self
    {
        self::$flash = [
            'title' => $title,
            'messages' => $messages,
            'display' => 'popup'
        ];

        return new self();
    }

    /**
     * toast
     *
     * @param  array|string $messages
     * @param  string $title
     * @return \Framework\Support\Notification
     */
    public static function toast($messages, string $title = '') : self
    {
        self::$flash = [
            'title' => $title,
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
        Session::create(config('app.name') . '_messages', self::$flash);
    }
    
    /**
     * error
     *
     * @return void
     */
    public function error(): void
    {
        self::$flash = array_merge(self::$flash, ['type' => 'danger']);
        Session::create(config('app.name') . '_messages', self::$flash);
    }
    
    /**
     * info
     *
     * @return void
     */
    public function info(): void 
    {
        self::$flash = array_merge(self::$flash, ['type' => 'primary']);
        Session::create(config('app.name') . '_messages', self::$flash);
    }
    
    /**
     * warning
     *
     * @return void
     */
    public function warning(): void 
    {
        self::$flash = array_merge(self::$flash, ['type' => 'warning']);
        Session::create(config('app.name') . '_messages', self::$flash);
    }
}