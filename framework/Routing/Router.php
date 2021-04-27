<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Routing;

use Closure;
use Exception;
use App\Helpers\Auth;
use Framework\Http\Request;
use Framework\Routing\View;
use Framework\System\Session;
use Framework\System\DependcyInjection;

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
     * check user access to protected routes
     *
     * @param  array $roles
     * @return void
     */
    private static function checkAccess(array $roles): void
    {
        if (!Auth::check()) {
            if (!empty(config('errors.views.403'))) {
                View::render(config('errors.views.403'), [], 403);
            }
                
            response()->send(__('no_access_permission', true), [], 403);
        }

        if (!in_array(Auth::get('role'), $roles)) {
            if (!empty(config('errors.views.403'))) {
                View::render(config('errors.views.403'), [], 403);
            }
                
            response()->send(__('no_access_permission', true), [], 403);
        }
    }
    
    /**
     * match routes and execute handlers
     *
     * @param  \Framework\Http\Request $request
     * @param  array $routes
     * @return void
     * 
     * @throws Exception
     */
    public static function dispatch(Request $request, array $routes): void
    {   
        if (empty($routes)) {
            throw new Exception('Routes not found');
        }

        foreach ($routes as $route => $options) {
            $request->method($request->inputs('request_method', $options['method']));

            if (preg_match('/' . strtoupper($options['method']) . '/', strtoupper($request->method()))) {
                if (self::match($route, $options, $request->uri(), $params)) {
                    //check access to protected routes
                    if (isset($options['protected'])) {
                        self::checkAccess($options['protected']);
                    }

                    //add route to browsing history and exclude API
                    if (!in_url('api')) {
                        Session::put('history', [$request->fullUri()]);
                    }

                    //execute routes middlewares if set
                    if (isset($options['middlewares'])) {
                        Middleware::check($route);
                    }

                    //handler is closure
                    if ($options['handler'] instanceof Closure) {
                        call_user_func_array($options['handler'], array_values($params));
                    } 
                    
                    //handler is view template
                    else if (is_string($options['handler'])) {
                        View::render($options['handler']);
                    }
                    
                    //handler is controller and action
                    else if (is_array($options['handler'])) {
                        list($controller, $action) = $options['handler'];

                        if (class_exists($controller) && method_exists($controller, $action)) {
                            (new DependcyInjection())->resolve($controller, $action, $params);
                        } else {
                            throw new Exception('Handler "' . $controller . '/' . $action . '" not found.');
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
    }
}
