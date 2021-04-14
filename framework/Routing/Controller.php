<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use App\Helpers\Activity;
use Framework\Http\Client;
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
     * send GET request
     *
     * @param  array $urls
     * @param  array $headers
     * @return \Framework\Http\Client
     */
    public function get(array $urls, array $headers = []): \Framework\Http\Client
    {
        return Client::get($urls, $headers);
    }

    /**
     * send POST request
     *
     * @param  array $urls
     * @param  array $headers
     * @param  array|null $data
     * @param  bool $json send data in json
     * @return \Framework\Http\Client
     */
    public function post(array $urls, array $headers = [], ?array $data = null, bool $json = false): \Framework\Http\Client
    {
        return Client::post($urls, $headers, $data, $json);
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
