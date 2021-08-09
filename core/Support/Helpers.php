<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Carbon\Carbon;
use Core\Http\Request;
use Core\Routing\View;
use Core\Http\Redirect;
use Core\Http\Response;
use Core\Routing\Route;
use Core\Support\Config;
use Core\Support\Cookies;
use Core\Support\Session;
use Core\Support\Storage;
use Core\Support\Encryption;

/**
 * Cookies management
 */

if (!function_exists('create_cookie')) {
    function create_cookie(string $name, string $value, int $expire = 3600, bool $secure = false, string $domain = '') 
    {
        return Cookies::create($name, $value, $expire, $secure, $domain);
	}
}

if (!function_exists('get_cookie')) {
	function get_cookie(string $name)
	{
        return Cookies::get($name);
	}
}

if (!function_exists('cookie_has')) {
	function cookie_has(string $name)
	{
		return Cookies::has($name);
	}
}

if (!function_exists('delete_cookie')) {
	function delete_cookie(string $name)
	{
		return Cookies::delete($name);
	}
}

/**
 * Sessions management
 */

if (!function_exists('create_session')) {
	function create_session(string $name, $data)
	{
		Session::create($name, $data);
	}
}

if (!function_exists('session_get')) {
	function session_get(string $name, $default = null)
	{
		return Session::get($name, $default);
	}
}

if (!function_exists('session_pull')) {
	function session_pull(string $name)
	{
		return Session::pull($name);
	}
}

if (!function_exists('session_put')) {
	function session_push(string $name, $data, $default = null)
	{
		Session::push($name, $data, $default);
	}
}

if (!function_exists('session_has')) {
	function session_has(string $name)
	{
		return Session::has($name);
	}
}

if (!function_exists('session_flush')) {
	function session_flush(string ...$names)
	{
		Session::flush(...$names);
	}
}

if (!function_exists('auth_attempts_exceeded')) {
    function auth_attempts_exceeded()
    {
        if (!config('security.auth.max_attempts')) {
            return false;
        }

        $unlock_timeout = session_get('auth_attempts_timeout');
        $attempts = session_get('auth_attempts');

        if (is_null($attempts) || is_null($unlock_timeout)) {
            return false;
        }

        return $attempts >= config('security.auth.max_attempts') && Carbon::parse($unlock_timeout)->gte(Carbon::now());
    }
}

if (!function_exists('auth')) {
	/**
	 * Get authenticated user session data
	 */
	function auth(string $key)
	{
        $user = session_get('user');

        if (is_null($user)) {
            return false;
        }

		return $user->{$key};
	}
}

/**
 * Security utils
 */

if (!function_exists('hash_pwd')) {    
    /**
     * Hash password
     */
    function hash_pwd(string $password)
    {
        return Encryption::hash($password);
    }
}

if (!function_exists('sanitize')) {
	/**
     * Sanitize html and others scripting languages
     */
    function sanitize(string $str)
    {
        $str = stripslashes($str);
        $str = htmlspecialchars($str);
        $str = strip_tags($str);

        return $str;
    }
}

if (!function_exists('generate_csrf_token')) {
    function generate_csrf_token()
    {
        if (session_has('csrf_token')) {
            $csrf_token = session_get('csrf_token');
        } else {
            $csrf_token = generate_token();
            create_session('csrf_token', $csrf_token);
        }

        return $csrf_token;
    }
}

if (!function_exists('csrf_token_input')) {
    /**
     * Generate crsf token html input tag
     */
    function csrf_token_input()
    {
        return '<input type="hidden" name="csrf_token" id="csrf_token" value="' . generate_csrf_token() . '">';
    }
}

if (!function_exists('csrf_token_meta')) {
    /**
     * Generate crsf token html meta tag
     */
    function csrf_token_meta()
    {
        return '<meta name="csrf_token" content="' . generate_csrf_token() . '">';
    }
}

if (!function_exists('method_input')) {
    /**
     * Set custom request method with html input tag
     */
    function method_input(string $method)
    {
        return '<input type="hidden" name="_method" value="' . $method . '">';
    }
}

if (!function_exists('valid_csrf_token')) {
    function valid_csrf_token(string $csrf_token)
    {
        return hash_equals(session_get('csrf_token'), $csrf_token);
    }
}

/**
 * Miscellaneous URL utils
 */

if (!function_exists('url')) {
	/**
	 * Generate abosulte url
	 */
	function url(string $uri, $params = null)
	{
        $url = config('app.url') . ltrim($uri, '/');
        $params = is_array($params) ? (empty($params) ? '' : implode('/', $params)) : $params;

		return is_null($params) ? $url : $url . '/' . $params;
	}
}

if (!function_exists('route_uri')) {
    function route_uri(string $name, $params = null)
    {
        $uri = '';
        $patterns = ['([a-zA-Z-_]+)', '(\d+)', '([^/]+)'];

        foreach (Route::$routes as $route => $options) {
            if (isset($options['name']) && !empty($options['name'])) {
                if ($name === $options['name']) {
                    $uri = explode(' ', $route, 2)[1];
                }
            }
        }
        
        if (empty($uri)) {
            throw new Exception('Route name "' . $name . '" is not defined.');
        }

        if (is_null($params)) {
            foreach ($patterns as $pattern) {
                if (strpos($uri, '+)?')) {
                    $pattern = "?$pattern?";
                }
                    
                if (strpos($uri, $pattern)) {
                    $uri = substr_replace($uri, '', strpos($uri, $pattern), strlen($pattern));
                }
            }
        }

        else {
            $params = is_array($params) ? $params : [$params];
            reset($params);

            foreach ($patterns as $pattern) {
                if (strpos($uri, '+)?')) {
                    $pattern = "?$pattern?";

                    if (!isset($params)) {
                        if (strpos($uri, $pattern)) {
                            $uri = substr_replace($uri, '', strpos($uri, $pattern), strlen($pattern));
                            next($params);
                            continue;
                        }
                    }
                }

                if (strpos($uri, $pattern)) {
                    $uri = substr_replace($uri, current($params), strpos($uri, $pattern), strlen($pattern));
                    next($params);
                }
            }
        }

        $uri = str_replace('//', '/', $uri);
        
        if ($uri !== '/') {
            $uri = rtrim($uri, '/');
        }

        return $uri;
    }
}

if (!function_exists('route')) {    
    /**
     * Get route absolute url
     */
    function route(string $name, $params = null)
    {
        return url(route_uri($name, $params));
    }
}

if (!function_exists('assets')) {    
    /**
     * Generate assets url from public path
     */
    function assets(string $asset, $params = null)
    {
        return url('public/' . $asset, $params);
    }
}

if (!function_exists('storage')) {    
    /**
     * Generate storage path url
     */
    function storage(string $path, $params = null)
    {
        return url('storage/' . $path, $params);
    }
}

if (!function_exists('resources')) {    
    /**
     * Generate resources path url
     */
    function resources(string $path, $params = null)
    {
        return url('resources/' . $path, $params);
    }
}

if (!function_exists('current_url')) {
	function current_url()
	{
		return url((new Request())->fullUri());
	}
}

if (!function_exists('url_contains')) {	
	function url_contains(string $str)
	{
        return preg_match('/' . $str . '/', explode('//', current_url())[1]);
	}
}

if (!function_exists('response')) {
    /**
     * Response helper function
     */
    function response(): \Core\Http\Response
    {
        return new Response();
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect helper function
     */
    function redirect(): \Core\Http\Redirect
    {
        return new Redirect();
    }
}

if (!function_exists('render')) {
    /**
     * Render view template helper
     */
    function render(string $view, array $data = [], int $code = 200)
    {
        View::render($view, $data, $code);
    }
}

/**
 * Miscellaneous utils
 */

if (!function_exists('real_path')) {    
    /**
     * Replace '.' by OS's directory separator
     */
    function real_path(string $path)
    {
        return str_replace('.', DS, $path);
    }
}

if (!function_exists('absolute_path')) {    
    /**
     * Generate absolute path
     */
    function absolute_path(string $path)
    {
        return APP_ROOT . real_path($path) . DS;
    }
}

if (!function_exists('slugify')) {
	/**
	 * @link   https://ourcodeworld.com/articles/read/253/creating-url-slugs-properly-in-php-including-transliteration-support-for-utf-8
	 */
	function slugify(string $str, string $separator = '-')
	{
		return strtolower(
			trim(
				preg_replace(
					'~[^0-9a-z]+~i',
					$separator,
					html_entity_decode(
						preg_replace(
							'~&([a-z]{1,2})(?:acute|cedil|circ|grave|lig|orn|ring|slash|th|tilde|uml);~i',
							'$1',
							htmlentities($str, ENT_QUOTES, 'UTF-8')
						),
						ENT_QUOTES,
						'UTF-8'
					)
				),
				$separator
			)
		);
	}
}

if (!function_exists('generate_token')) {
	function generate_token(int $length = 32)
	{
		return bin2hex(random_bytes($length));
	}
}

if (!function_exists('config')) {	
	/**
	 * Read configuration
	 */
	function config(string $data, $default = null)
	{
        if (strpos($data, '.') === false) {
            return null;
        }

        $file = substr($data, 0, strpos($data, '.'));
        $data = substr($data, strpos($data, '.') + 1, strlen($data));
        $path = absolute_path('config') . $file . '.php';

        return Config::readFile($path, $data, $default);
	}
}

if (!function_exists('__')) {    
    /**
     * Translate words and expressions
     */
    function __(string $expr, array $data = [])
    {
        return Config::readTranslations($expr, $data);
    }
}

if (!function_exists('get_file_extension')) {	
	function get_file_extension(string $file)
	{
		if (empty($file) || strpos($file, '.') === false) {
            return '';
		}
		
		$file_ext = explode('.', $file);
		return $file_ext === false ? '' : end($file_ext);
	}
}

if (!function_exists('get_file_name')) {	
	function get_file_name(string $file)
	{
		if (empty($file) || strpos($file, '.') === false) {
            return '';
		}
		
		$filename = explode('.', $file);
		return $filename === false ? '' : $filename[0];
	}
}

if (!function_exists('save_log')) {
	function save_log(string $message)
	{
        if (!Storage::path(config('storage.logs'))->isDir()) {
            Storage::path(config('storage.logs'))->createDir();
        }

        error_log($message);
    }
}

if (!function_exists('env')) {    
    /**
     * Get environnement variable key
     */
    function env(string $key, $default = null)
    {
        return Config::readEnv($key, $default);
    }
}
