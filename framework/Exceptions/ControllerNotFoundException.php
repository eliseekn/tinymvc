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
 * ControllerNotFoundException
 * 
 * Exception that occurs when controller class or/and method not found
 */
class ControllerNotFoundException extends Exception
{    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(string $controller)
    {
        $this->message = 'Controller <b>' . $controller . '</b> class or/and method not found in <b>"app/Controllers"</b>.';
    }
}