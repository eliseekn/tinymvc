<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Support;

/**
 * Manage alerts flash messages
 */
class Alert
{
    /**
     * @var array
     */
    protected static $flash = [];

    /**
     * default alert
     *
     * @param  array|string $messages
     * @param  string $title
     * @param  mixed $dissmiss
     * @return \Framework\Support\Notification
     */
    public static function default($messages, string $title = '', bool $dissmiss = true): self
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
     * popup alert
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
     * toast alert
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
     * display success alert
     *
     * @return void
     */
    public function success(): void
    {
        self::$flash = array_merge(self::$flash, ['type' => 'success']);
        Session::create(config('app.name') . '_messages', self::$flash);
    }
    
    /**
     * display error alert
     *
     * @return void
     */
    public function error(): void
    {
        self::$flash = array_merge(self::$flash, ['type' => 'danger']);
        Session::create(config('app.name') . '_messages', self::$flash);
    }
    
    /**
     * display info alert
     *
     * @return void
     */
    public function info(): void 
    {
        self::$flash = array_merge(self::$flash, ['type' => 'primary']);
        Session::create(config('app.name') . '_messages', self::$flash);
    }
    
    /**
     * display warning alert
     *
     * @return void
     */
    public function warning(): void 
    {
        self::$flash = array_merge(self::$flash, ['type' => 'warning']);
        Session::create(config('app.name') . '_messages', self::$flash);
    }
}