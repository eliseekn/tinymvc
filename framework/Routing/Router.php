<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use Closure;
use Exception;
use Framework\Http\Request;
use Framework\Routing\View;
use Framework\Http\Response;
use Framework\Support\Session;

/**
 * Routing system
 */
class Router
{
    /**
     * set sesssion history 
     *
     * @return void
     */
    public static function  history(Request $request): void
    {
        $url = Session::get('history');

        //excludes api uri from browsing history
        if (!in_url('api')) {
            if (empty($url)) {
                $url = [$request->uri(true)];
            } else {
                $url[] = $request->uri(true);
            }
        }

        Session::create('history', $url);
    }
    
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
            $urls = explode('/', $route);

            foreach ($urls as $url) {
                foreach ($options['parameters'] as $parameter => $type) {
                    if (strpos($url, '?') === false) {
                        if ($url === '{' . $parameter . '}') {
                            $route = str_replace($url, $type, $route);
                        }
                    } else {
                        if ($url === '?{' . $parameter . '}?') {
                            $route = str_replace($url, '?' . $type . '?', $route);
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
     * @param  array $routes routes
     * @return void
     */
    public static function dispatch(Request $request, array $routes): void
    {
        if (!empty($routes)) {
            foreach ($routes as $route => $options) {
                $request->method($request->inputs('request_method', $options['method']));

                if (preg_match('/' . strtoupper($options['method']) . '/', strtoupper($request->method()))) {
                    if (self::match($route, $options, $request->uri(), $params)) {
                        //check for middlewares to execute
                        Middleware::check($route);

                        if ($options['handler'] instanceof Closure) {
                            //execute function with parameters
                            call_user_func_array($options['handler'], array_values($params));
                        } else {
                            list($controller, $action) = $options['handler'];

                            //chekc if controller class and method exist
                            if (class_exists($controller) && method_exists($controller, $action)) {
                                (new ReflectionResolver())->resolve($controller, $action, $params);
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
