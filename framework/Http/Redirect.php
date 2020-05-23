<?php

namespace Framework\Http;

use Framework\Core\View;
use Framework\Core\Route;

class Redirect
{    
    /**
     * url to redirect
     *
     * @var string
     */
    private static $redirect_url = '';

    /**
     * redirect to route
     *
     * @param  string $name route name or Controller@action
     * @param  array $params parameters 
     * @return void
     */
    public static function toRoute(string $name, array $params = [])
    {
        $methods = ['GET /', 'POST /', 'PUT /', 'DELETE /', 'PATCH /', 'OPTIONS /', 'ANY /'];
        $params = empty($params) ? '' : implode('/', $params);

        if (array_key_exists($name, Route::$names)) {
            $route = Route::$names[$name];

            if (in_array($route, Route::$routes, true)) {
                $url = array_search($route, Route::$routes, true);
                $url = str_replace($methods, '', $url);
                self::$redirect_url = empty($params) ? $url : $url . '/' . $params;
            } else {
                View::render('error_404');
            }
        } else if (in_array($name, Route::$routes, true)) {
            $url = array_search($name, Route::$routes, true);
            $url = str_replace($methods, '', $url);
            self::$redirect_url = empty($params) ? $url : $url . '/' . $params;
        } else {
            View::render('error_404');
        }

        return new self();
    }
    
    /**
     * go to previous page
     *
     * @return void
     */
    public static function back()
    {
        $browsing_history = get_session('browsing_history');

        if (!empty($browsing_history)) {
            $current_url = end($browsing_history);
            $key = array_search($current_url, $browsing_history);
            self::$redirect_url = $browsing_history[$key - 1];
        }

        return new self();
    }
    
    /**
     * refresh page
     *
     * @return void
     */
    public static function refresh()
    {
        $browsing_history = get_session('browsing_history');

        if (!empty($browsing_history)) {
            self::$redirect_url = end($browsing_history);
        }

        return new self();
    }
    
    /**
     * redirects with session flash message
     *
     * @param  string $title title of message
     * @param  string $content content of message
     * @return void
     */
    public function withMessage(string $title, $content): void
    {
        create_session('flash_messages', [
            $title => $content
        ]);

        redirect_to(self::$redirect_url);
    }
    
    /**
     * redirects without session flash message
     *
     * @return void
     */
    public function only(): void
    {
        redirect_to(self::$redirect_url);
    }
}