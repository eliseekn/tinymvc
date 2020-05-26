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
 * RouteNotFoundException
 * 
 * Exception that occurs when route name is not set in configuration 
 */
class RouteNotFoundException extends Exception
{    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(string $route)
    {
        $this->message = 'Route name <b>' . $route . '</b> not found in <b>"config/routes.php"</b>.';
    }
}