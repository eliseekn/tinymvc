<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use App\Helpers\Activity;
use Framework\Support\Alert;

/**
 * Main controller class
 */
class Controller
{
    /**
     * render view template
     *
     * @param  string $view
     * @param  array $data
     * @param  int $code
     * @return void
     */
    public function render(string $view, array $data = [], int $code = 200): void
    {
        View::render($view, $data, $code);
    }
    
    /**
     * generate alert
     *
     * @param  string $type
     * @param  string $message
     * @return void
     */
    public function toast(string $type, string $message): void
    {
        Alert::toast($message)->$type();
    }
    
    /**
     * log user activity
     *
     * @param  string $action
     * @return void
     */
    public function log(string $action): void
    {
        Activity::log($action);
    }
}
