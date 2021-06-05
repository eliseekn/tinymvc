<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

use Core\System\Session;

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
     * @return \Core\Support\Alert
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
     * @return \Core\Support\Alert
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
     * @return \Core\Support\Alert
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
     * @return void
     */
    public function success(): void
    {
        self::$alert += ['type' => 'success'];
        Session::create('alert', self::$alert);
    }
    
    /**
     * display error alert
     *
     * @return void
     */
    public function error(): void
    {
        self::$alert += ['type' => 'danger'];
        Session::create('alert', self::$alert);
    }
    
    /**
     * display info alert
     *
     * @return void
     */
    public function info(): void 
    {
        self::$alert += ['type' => 'primary',];
        Session::create('alert', self::$alert);
    }
    
    /**
     * display warning alert
     *
     * @return void
     */
    public function warning(): void 
    {
        self::$alert += ['type' => 'warning',];
        Session::create('alert', self::$alert);
    }
}