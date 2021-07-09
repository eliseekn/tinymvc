<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

use Core\Support\Session;

/**
 * Manage alerts messages
 */
class Alert
{
    protected static $alert = [];

    public static function default($message, bool $dismiss = true): self
    {
        self::$alert = [
            'message' => $message,
            'display' => 'default',
            'dismiss' => $dismiss
        ];

        return new self();
    }
    
    public static function popup($message): self
    {
        self::$alert = [
            'message' => $message,
            'display' => 'popup'
        ];

        return new self();
    }

    public static function toast($message) : self
    {
        self::$alert = [
            'message' => $message,
            'display' => 'toast'
        ];

        return new self();
    }
    
    public function success()
    {
        self::$alert += ['type' => 'success'];
        Session::create('alert', self::$alert);
    }
    
    public function error()
    {
        self::$alert += ['type' => 'danger'];
        Session::create('alert', self::$alert);
    }
    
    public function info() 
    {
        self::$alert += ['type' => 'primary',];
        Session::create('alert', self::$alert);
    }
    
    public function warning() 
    {
        self::$alert += ['type' => 'warning',];
        Session::create('alert', self::$alert);
    }
}