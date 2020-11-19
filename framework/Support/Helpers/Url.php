<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

use Framework\Routing\Route;

/**
 * Miscellaneous URL utils functions
 */

if (!function_exists('absolute_url')) {
	/**
	 * generate abosulte url
	 *
	 * @param  string $url
	 * @return string
	 */
	function absolute_url(string $url): string
	{
		if (empty($url)) {
			$url = '/';
		}

		if (strlen($url) > 1) {
			if ($url[0] !== '/') {
				$url = '/' . $url;
			}
		}

		return config('app.url') . $url;
	}
}

if (!function_exists('route_url')) {	
	/**
	 * generate absolute url from route name
	 *
	 * @param  string $name
	 * @param  array|string $params
	 * @return string
	 */
	function route_url(string $name, $params = null): string
	{
		$params = is_array($params) ? (empty($params) ? '' : implode('/', $params)) : $params;

        //search key from value in a multidimensional array
        //https://www.php.net/manual/en/function.array-search.php
        $url = array_search(
            $name, array_map(
                function ($val) {
                    if (isset($val['name'])) {
                        return $val['name'];
                    }
                },
                Route::$routes
            )
        );

        if (empty($url)) {
            throw new Exception('Route "' . $name . '" not found.');
        }

        return is_null($params) ? absolute_url($url) : absolute_url($url . '/' . $params);
	}
}

if (!function_exists('redirect_to')) {
	/**
	 * redirect to another location
	 *
	 * @param  string $location
	 * @return void
	 */
	function redirect_to(string $location): void
	{
		header('Location: ' . absolute_url($location));
		exit();
	}
}

if (!function_exists('current_url')) {
	/**
	 * get current url
	 *
	 * @return string
	 * @link   https://stackoverflow.com/questions/6768793/get-the-full-url-in-php#6768831
	 */
	function current_url(): string
	{
		return 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
	}
}

if (!function_exists('url_exists')) {	
	/**
	 * check if current url contains specific string
	 *
	 * @param  string $str
	 * @return bool
	 */
	function url_exists(string $str): bool
	{
        return preg_match('/' . $str . '/', current_url());
	}
}
