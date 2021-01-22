<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use Framework\Http\Client;
use Framework\Http\Redirect;
use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Routing\Middleware;

/**
 * Main controller class
 */
class Controller
{
    /**
     * @var \Framework\Http\Request $request
     */
    public $request;

    /**
     * __construct
     *
     * @param  \Framework\Http\Request $request
     * @return void
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

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
     * send HTTP headers only
     *
     * @param  array $headers
     * @param  int $status_code
     * @return void
     */
    public static function headers(array $headers, int $status_code = 200): void
    {
        Response::headers($headers, $status_code);
    }
    
    /**
     * send HTTP response
     *
     * @param  mixed $body
     * @param  bool $json
     * @param  array $headers
     * @param  int $status_code
     * @return void
     */
    public function response($body, bool $json = false, array $headers = [], int $status_code = 200): void
    {
        Response::send($body, $json, $headers, $status_code);
    }
    
    /**
     * redirect to url or redirect back
     *
     * @param  string|null $url
     * @param  mixed $params
     * @return Framework\Http\Redirect
     */
    public function redirect(?string $url = null, $params = null): ?\Framework\Http\Redirect
    {
        return is_null($url) ? Redirect::back() : Redirect::url($url, $params);
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
     * @param  string[] $middlewares
     * @return void
     */
    public function middlewares(string ...$middlewares): void
    {
        foreach ($middlewares as $middleware) {
            Middleware::execute($middleware, new Request());
        }
    }
}
