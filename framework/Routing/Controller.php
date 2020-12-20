<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use Framework\HTTP\Request;
use Framework\HTTP\Redirect;
use Framework\HTTP\Response;

/**
 * Main controller class
 */
class Controller
{
    /**
     * @var \Framework\HTTP\Request $request
     */
    public $request;

    /**
     * __construct
     *
     * @param  \Framework\HTTP\Request $request
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
     * call middlewares
     *
     * @param  string[] $middlewares
     * @return void
     */
    public function middlewares(string ...$middlewares): void
    {
        foreach ($middlewares as $middleware) {
            Middleware::execute($middleware);
        }
    }
}
