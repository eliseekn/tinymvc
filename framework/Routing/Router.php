<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use App\Helpers\Auth;
use Closure;
use Exception;
use Framework\Http\Request;
use Framework\Routing\View;
use Framework\System\DependcyInjection;
use Framework\System\Session;

/**
 * Routing system
 */
class Router
{
    /**
     * check if request uri match with routes
     *
     * @param  string $route
     * @param  array $options
     * @param  string $uri
     * @param  string[] &$matches
     * @return bool
     */
    private static function match(string $route, array $options, string $uri, ?array &$matches = null): bool
    {
        if (isset($options['parameters']) && !empty($options['parameters'])) {
            $pieces = explode('/', $route);

            foreach ($pieces as $piece) {
                foreach ($options['parameters'] as $parameter => $type) {
                    if (strpos($piece, '?') === false) {
                        if ($piece === '{' . $parameter . '}') {
                            $route = str_replace($piece, $type, $route);
                        }
                    } else {
                        if ($piece === '?{' . $parameter . '}?') {
                            $route = str_replace($piece, '?' . $type . '?', $route);
                        }
                    }
                }
            }
        }

        if (preg_match('#^' . $route . '$#', $uri, $matches)) {
            array_shift($matches);
            return true;
        }

        return false;
    }
    
    /**
     * match routes and execute handlers
     *
     * @param  array $routes
     * @return void
     */
    public static function dispatch(Request $request, array $routes): void
    {
        if (!empty($routes)) {
            foreach ($routes as $route => $options) {
                $request->method($request->inputs('request_method', $options['method']));

                if (preg_match('/' . strtoupper($options['method']) . '/', strtoupper($request->method()))) {
                    if (self::match($route, $options, $request->uri(), $params)) {
                        //add route to browsing history and exclude API
                        if (!in_url('api')) {
                            Session::put('history', [$request->fullUri()]);
                        }

                        //execute routes middlewares if set
                        Middleware::check($route);

                        //handler is closure
                        if ($options['handler'] instanceof Closure) {
                            call_user_func_array($options['handler'], array_values($params));
                        } 
                        
                        //handler is view template
                        else if (is_string($options['handler'])) {
                            View::render($options['handler']);
                        }
                        
                        //handler is controller and method
                        else if (is_array($options['handler'])) {
                            list($controller, $method) = $options['handler'];

                            if (class_exists($controller) && method_exists($controller, $method)) {
                                (new DependcyInjection())->resolve($controller, $method, $params);
                            } else {
                                throw new Exception('Handler "' . $controller . '" not found.');
                            }
                        }
                    }
                }
            }

            //send 404 response
            if (!empty(config('errors.views.404'))) {
                View::render(config('errors.views.404'), [], 404);
            } else {
                response()->send('The page you have requested does not exists', [], 404);
            }
        } else {
            throw new Exception('No route defines in configuration');
        }
    }
}
