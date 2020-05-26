<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Exceptions;

use Exception;

/**
 * InvalidMiddlewareException
 * 
 * Exception that occurs when middleware is not set properly
 */
class InvalidMiddlewareException extends Exception
{    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(string $middleware)
    {
        $this->message = 'Middleware name <b>' . $middleware . '</b> not found in <b>"config/middlewares.php"</b>.';
    }
}