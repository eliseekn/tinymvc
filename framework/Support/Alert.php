<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Support;

/**
 * Manage alerts messages
 */
class Alert
{
    /**
     * @var array
     */
    protected static $alert = [];

    /**
     * default alert
     *
     * @param  array|string $message
     * @param  mixed $dismiss
     * @return \Framework\Support\Alert
     */
    public static function default($message, bool $dismiss = true): self
    {
        self::$alert = [
            'message' => $message,
            'display' => 'default',
            'dismiss' => $dismiss
        ];

        return new self();
    }
    
    /**
     * popup alert
     *
     * @param  array|string $message
     * @return \Framework\Support\Alert
     */
    public static function popup($message): self
    {
        self::$alert = [
            'message' => $message,
            'display' => 'popup'
        ];

        return new self();
    }

    /**
     * toast alert
     *
     * @param  array|string $message
     * @return \Framework\Support\Alert
     */
    public static function toast($message) : self
    {
        self::$alert = [
            'message' => $message,
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
        self::$alert = array_merge(self::$alert, [
            'type' => 'success',
            'title' => $title,
        ]);

        Session::create('alerts', self::$alert);
    }
    
    /**
     * display error alert
     *
     * @param  string $title
     * @return void
     */
    public function error(string $title = 'Error'): void
    {
        self::$alert = array_merge(self::$alert, [
            'type' => 'danger',
            'title' => $title,
        ]);
        
        Session::create('alerts', self::$alert);
    }
    
    /**
     * display info alert
     *
     * @param  string $title
     * @return void
     */
    public function info(string $title = 'Info'): void 
    {
        self::$alert = array_merge(self::$alert, [
            'type' => 'primary',
            'title' => $title,
        ]);
        
        Session::create('alerts', self::$alert);
    }
    
    /**
     * display warning alert
     *
     * @param  string $title
     * @return void
     */
    public function warning(string $title = 'Warning'): void 
    {
        self::$alert = array_merge(self::$alert, [
            'type' => 'warning',
            'title' => $title,
        ]);
        
        Session::create('alerts', self::$alert);
    }
}