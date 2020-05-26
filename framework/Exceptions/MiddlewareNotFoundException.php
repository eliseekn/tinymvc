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
 * MiddlewareNotFoundException
 * 
 * Exception that occurs when middleware class or/and method not found
 */
class MiddlewareNotFoundException extends Exception
{    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(string $middleware)
    {
        $this->message = 'Middleware <b>' . $middleware . '</b> class or/and method "handle" not found in <b>"app/Middlewares"</b>.';
    }
}