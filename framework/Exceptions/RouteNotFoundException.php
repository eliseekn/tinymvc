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
        $this->message = $this->stylish('Route name <b>' . $route . '</b> not found in <b>"config/routes.php"</b>.');
    }
    
    /**
     * apply style to exception message
     *
     * @param  string $message
     * @return string
     */
    private function stylish(string $message): string
    {
        $str = '<div style="padding:.5em;">';
        $str .= '<div style="color: #721c24; background-color: #f8d7da; border-color: #721c24; border: 1px solid #721c24; border-radius: .25rem; padding: .75rem 1.25rem; margin-bottom: 1rem;">';
        $str .= $message;
        $str .= '</div>';
        $str .= '</div>';

        return $str;
    }
}