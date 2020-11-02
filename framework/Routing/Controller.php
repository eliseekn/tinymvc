<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Routing;

use Framework\HTTP\Redirect;
use Framework\HTTP\Response;
use Framework\Support\Alert;

/**
 * Main controller class
 */
class Controller
{
    /**
     * render view
     *
     * @param  string $view
     * @param  array $data
     * @return void
     */
    public function render(string $view, array $data = []): void
    {
        View::render($view, $data);
    }
    
    /**
     * redirect
     *
     * @param  string $to
     * @return \Framework\HTTP\Redirect
     */
    public function redirect(string $to = ''): \Framework\HTTP\Redirect
    {
        if (empty($to)) {
            return Redirect::back();
        } else {
            $url = array_search(
                $to, array_map(
                    function ($val) {
                        if (isset($val['name'])) {
                            return $val['name'];
                        }
                    },
                    Route::$routes
                )
            );

            return !empty($url) ? Redirect::toRoute($to) : Redirect::toUrl($to);
        }
    }
        
    /**
     * send http response
     *
     * @param  mixed $body
     * @param  array $headers
     * @param  int $status_code
     * @return void
     */
    public function response($body, array $headers = [],int $status_code = 200): void
    {
        Response::send($body, $headers, $status_code);
    }
    
    /**
     * send json http response
     *
     * @param  array $body
     * @param  array $headers
     * @param  int $status_code
     * @return void
     */
    public function json(array $body, array $headers = [],int $status_code = 200): void
    {
        Response::sendJson($body, $headers, $status_code);
    }

    /**
     * display alert
     *
     * @param  mixed $messages
     * @param  bool $dismiss
     * @return \Framework\Support\Alert
     */
    public function alert($messages, bool $dismiss = true): \Framework\Support\Alert
    {
        return Alert::default($messages, $dismiss);
    }
    
    /**
     * display toast
     *
     * @param  mixed $messages
     * @return \Framework\Support\Alert
     */
    public function toast($messages): \Framework\Support\Alert
    {
        return Alert::toast($messages);
    }
    
    /**
     * dispaly popup
     *
     * @param  mixed $messages
     * @return \Framework\Support\Alert
     */
    public function popup($messages): \Framework\Support\Alert
    {
        return Alert::popup($messages);
    }
}
