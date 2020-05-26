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
 * RoutesNotDefineException
 * 
 * Exception that occurs when no route set in configuration 
 */
class RoutesNotDefineException extends Exception
{    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->message = 'No route defines in <b>"config/routes.php"</b>.';
    }
}