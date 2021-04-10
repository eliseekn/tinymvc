<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use App\Helpers\Activity;
use Framework\Http\Client;
use Framework\Http\Redirect;
use Framework\Http\Response;
use Framework\Support\Alert;
use Framework\Database\Model;
use Framework\Routing\Middleware;

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
    public function render(string $view, array $data = [], int $status_code = 200): void
    {
        View::render($view, $data, $status_code);
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
     * call middlewares
     *
     * @param  mixed $middlewares
     * @return void
     */
    public function middlewares(...$middlewares): void
    {
        foreach ($middlewares as $middleware) {
            Middleware::execute($middleware);
        }
    }
    
    /**
     * create new model instance
     *
     * @param  string $table
     * @return \Framework\Database\Model
     */
    public function model(string $table): \Framework\Database\Model
    {
        return new Model($table);
    }
    
    /**
     * alert
     *
     * @param  string $type
     * @param  string $message
     * @return \Framework\Support\Alert
     */
    public function alert(string $type = 'default', string $message): \Framework\Support\Alert
    {
        return Alert::$type($message);
    }
    
    /**
     * log
     *
     * @param  string $action
     * @return void
     */
    public function log(string $action): void
    {
        Activity::log($action);
    }
}
