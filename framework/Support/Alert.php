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
     * @return \Framework\Support\Alert
     */
    public static function default($messages, bool $dissmiss = true): self
    {
        self::$flash = [
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
     * @return \Framework\Support\Alert
     */
    public static function popup($messages): self
    {
        self::$flash = [
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
     * @return \Framework\Support\Alert
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
     * display success alert
     *
     * @param  string $title
     * @return void
     */
    public function success(string $title = 'Success'): void
    {
        self::$flash = array_merge(self::$flash, [
            'type' => 'success',
            'title' => $title,
        ]);

        Session::create(config('app.name') . '_messages', self::$flash);
    }
    
    /**
     * display error alert
     *
     * @param  string $title
     * @return void
     */
    public function error(string $title = 'Error'): void
    {
        self::$flash = array_merge(self::$flash, [
            'type' => 'danger',
            'title' => $title,
        ]);
        
        Session::create(config('app.name') . '_messages', self::$flash);
    }
    
    /**
     * display info alert
     *
     * @param  string $title
     * @return void
     */
    public function info(string $title = 'Info'): void 
    {
        self::$flash = array_merge(self::$flash, [
            'type' => 'primary',
            'title' => $title,
        ]);
        
        Session::create(config('app.name') . '_messages', self::$flash);
    }
    
    /**
     * display warning alert
     *
     * @param  string $title
     * @return void
     */
    public function warning(string $title = 'Warning'): void 
    {
        self::$flash = array_merge(self::$flash, [
            'type' => 'warning',
            'title' => $title,
        ]);
        
        Session::create(config('app.name') . '_messages', self::$flash);
    }
}