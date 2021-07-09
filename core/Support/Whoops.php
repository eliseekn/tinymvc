<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

use Whoops\Run;
use Exception as BaseException;
use Whoops\Handler\PrettyPageHandler;

class Exception extends BaseException {}

/**
 * Register Whoops
 */
class Whoops 
{    
    /**
     * Register whoops instance
     */
    public static function register()
    {
        $run = new Run();
        $handler = new PrettyPageHandler();
        $handler->setApplicationPaths([APP_ROOT]);
        $run->pushHandler($handler);
        $run->register();
    }
}

