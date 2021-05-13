<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Carbon\Carbon;
use Framework\Http\Request;
use Framework\Routing\View;
use Configula\ConfigFactory;
use Framework\Http\Redirect;
use Framework\Http\Response;
use Framework\Routing\Route;
use Framework\System\Config;
use Framework\System\Cookies;
use Framework\System\Session;
use Framework\System\Storage;
use Framework\System\Encryption;

/**
 * Cookies management functions
 */

if (!function_exists('create_cookie')) {
	/**
	 * create cookie and set value
	 *
	 * @param  string $name
	 * @param  string $value
	 * @param  int $expire in seconds
	 * @param  bool $secure
	 * @param  string $domain
	 * @return bool
	 */
    function create_cookie(string $name, string $value, int $expire = 3600, bool $secure = false, string $domain = ''): bool 
    {
        return Cookies::create($name, $value, $expire, $secure, $domain);
	}
}

if (!function_exists('get_cookie')) {
	/**
	 * return cookie value
	 *
	 * @param  string $name
	 * @return string
	 */
	function get_cookie(string $name): string
	{
        return Cookies::get($name);
	}
}

if (!function_exists('cookie_has')) {
	/**
	 * check if cookie exists
	 *
	 * @param  string $name
	 * @return bool
	 */
	function cookie_has(string $name): bool
	{
		return Cookies::has($name);
	}
}

if (!function_exists('delete_cookie')) {
	/**
	 * delete cookie by name
	 *
	 * @param  string $name
	 * @return bool
	 */
	function delete_cookie(string $name): bool
	{
		return Cookies::delete($name);
	}
}

/**
 * Sessions management functions
 */

if (!function_exists('create_session')) {
	/**
	 * create session data
	 *
	 * @param  string $name
	 * @param  mixed $data
	 * @return void
	 */
	function create_session(string $name, $data): void
	{
		Session::create($name, $data);
	}
}

if (!function_exists('session_get')) {
	/**
	 * get session data
	 *
	 * @param  string $name
	 * @param  mixed $default
	 * @return mixed
	 */
	function session_get(string $name, $default = null)
	{
		return Session::get($name, $default);
	}
}

if (!function_exists('session_pull')) {
	/**
	 * get session data and close it
	 *
	 * @param  string $name
	 * @return mixed
	 */
	function session_pull(string $name)
	{
		return Session::pull($name);
	}
}

if (!function_exists('session_put')) {
	/**
	 * add data to session or create if empty
	 *
	 * @param  string $name
	 * @param  mixed $data
	 * @param  mixed $default
	 * @return mixed
	 */
	function session_put(string $name, $data, $default = null): void
	{
		Session::put($name, $data, $default);
	}
}

if (!function_exists('session_has')) {
	/**
	 * check if session exists
	 *
	 * @param  string $name
	 * @return bool
	 */
	function session_has(string $name): bool
	{
		return Session::has($name);
	}
}

if (!function_exists('session_flush')) {
	/**
	 * flush session
	 *
	 * @param  string[] $names
	 * @return void
	 */
	function session_flush(string ...$names): void
	{
		Session::flush(...$names);
	}
}

if (!function_exists('auth_attempts_exceeded')) {    
    /**
     * check if auth attempts exceeded
     *
     * @return bool
     */
    function auth_attempts_exceeded(): bool
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
	 * get authenticated user session data
	 *
     * @param  string $key
	 * @return mixed
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
 * Security utils functions
 */

if (!function_exists('pwd_hash')) {    
    /**
     * password hash helper
     *
     * @param  string $password
     * @return string
     */
    function pwd_hash(string $password): string
    {
        return Encryption::hash($password);
    }
}

if (!function_exists('sanitize')) {
	/**
     * sanitize html and others scripting languages
     *
     * @param  string $str
     * @return string
     */
    function sanitize(string $str): string
    {
        $str = stripslashes($str);
        $str = htmlspecialchars($str);
        $str = strip_tags($str);
        return $str;
    }
}

if (!function_exists('generate_csrf_token')) {
    /**
     * generate crsf token
     *
     * @return string
     */
    function generate_csrf_token(): string
    {
        if (session_has('csrf_token')) {
            $csrf_token = session_get('csrf_token');
        } else {
            $csrf_token = bin2hex(random_bytes(32));
            create_session('csrf_token', $csrf_token);
        }

        return $csrf_token;
    }
}

if (!function_exists('csrf_token_input')) {
    /**
     * generate crsf token html input
     *
     * @return string
     */
    function csrf_token_input(): string
    {
        return '<input type="hidden" name="csrf_token" id="csrf_token" value="' . generate_csrf_token() . '">';
    }
}

if (!function_exists('csrf_token_meta')) {
    /**
     * generate crsf token html meta
     *
     * @return string
     */
    function csrf_token_meta(): string
    {
        return '<meta name="csrf_token" content="' . generate_csrf_token() . '">';
    }
}

if (!function_exists('method_input')) {
    /**
     * generate method input
     *
     * @param  string $method
     * @return string
     */
    function method_input(string $method): string
    {
        return '<input type="hidden" name="request_method" value="' . $method . '">';
    }
}

if (!function_exists('valid_csrf_token')) {
    /**
     * check if crsf token is valid
     *
     * @param  string $csrf_token
     * @return bool
     */
    function valid_csrf_token(string $csrf_token): bool
    {
        return hash_equals(session_get('csrf_token'), $csrf_token);
    }
}

/**
 * Miscellaneous URL utils functions
 */

if (!function_exists('url')) {
	/**
	 * generate abosulte url
	 *
	 * @param  string $uri
	 * @return string
	 */
	function url(string $uri, $params = null): string
	{
        $url = config('app.url') . ltrim($uri, '/');
        $params = is_array($params) ? (empty($params) ? '' : implode('/', $params)) : $params;

		return is_null($params) ? $url : $url . '/' . $params;
	}
}

if (!function_exists('route_uri')) {    
    /**
     * get route uri
     *
     * @param  string $name
     * @param  mixed $params
     * @return string
     */
    function route_uri(string $route, $params = null): string
    {
        if (!isset(Route::$names[$route])) {
            throw new Exception('Route name "' . $route . '" is not defined.');
        }

        $uri = Route::$names[$route];
        $patterns = ['([a-zA-Z-_]+)', '(\d+)', '([^/]+)'];

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
     * get route absolute url
     *
     * @param  string $name
     * @param  mixed $params
     * @return string
     */
    function route(string $route, $params = null): string
    {
        return url(route_uri($route, $params));
    }
}

if (!function_exists('assets')) {    
    /**
     * generate assets url from public folder
     *
     * @param  string $asset
     * @param  mixed $params
     * @return string
     */
    function assets(string $asset, $params = null): string
    {
        return url('public/' . $asset, $params);
    }
}

if (!function_exists('storage')) {    
    /**
     * generate storage url
     *
     * @param  string $path
     * @param  mixed $params
     * @return string
     */
    function storage(string $path, $params = null): string
    {
        return url('storage/' . $path, $params);
    }
}

if (!function_exists('resources')) {    
    /**
     * generate resources url
     *
     * @param  string $path
     * @param  mixed $params
     * @return string
     */
    function resources(string $path, $params = null): string
    {
        return url('resources/' . $path, $params);
    }
}

if (!function_exists('current_url')) {
	/**
	 * get current url
	 *
	 * @return string
	 */
	function current_url(): string
	{
		return url((new Request())->fullUri());
	}
}

if (!function_exists('in_url')) {	
	/**
	 * check if current url contains specific string
	 *
	 * @param  string $str
	 * @return bool
	 */
	function in_url(string $str): bool
	{
        return preg_match('/' . $str . '/', explode('//', current_url())[1]);
	}
}

if (!function_exists('response')) {
    /**
     * Response helper function
     *
     * @return \Framework\Http\Response
     */
    function response(): \Framework\Http\Response
    {
        return new Response();
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect helper function
     *
     * @return \Framework\Http\Redirect
     */
    function redirect(): \Framework\Http\Redirect
    {
        return new Redirect();
    }
}

if (!function_exists('render')) {
    /**
     * render view template
     *
     * @param  string $view
     * @param  array $data
     * @param  int $code
     * @return void
     */
    function render(string $view, array $data = [], int $code = 200): void
    {
        View::render($view, $data, $code);
    }
}

/**
 * Miscellaneous utils functions
 */

if (!function_exists('real_path')) {    
    /**
     * replace '.' by DS
     *
     * @param  string $path
     * @return string
     */
    function real_path(string $path): string
    {
        return str_replace('.', DS, $path);
    }
}

if (!function_exists('absolute_path')) {    
    /**
     * get absolute path
     *
     * @param  string $path
     * @return string
     */
    function absolute_path(string $path): string
    {
        return APP_ROOT . real_path($path) . DS;
    }
}

if (!function_exists('slugify')) {
	/**
	 * generate slug from string with utf8 encoding
	 *
	 * @param  string $str
	 * @param  string $separator
	 * @return string
	 * @link   https://ourcodeworld.com/articles/read/253/creating-url-slugs-properly-in-php-including-transliteration-support-for-utf-8
	 */
	function slugify(string $str, string $separator = '-'): string
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

if (!function_exists('random_string')) {
	/**
	 * random string generator
	 *
	 * @param  int $length
	 * @param  bool $alphanumeric
	 * @return string
	 * @link   https://www.php.net/manual/en/function.str-shuffle.php
	 */
	function random_string(int $length = 10, bool $alphanumeric = false): string
	{
		$chars = 'abcefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$chars .= $alphanumeric ? '0123456789' : '';
		return substr(str_shuffle($chars), 0, $length);
	}
}

if (!function_exists('config')) {	
	/**
	 * read configuration
	 *
	 * @param  string $data
     * @param  mixed $default
	 * @return mixed
	 */
	function config(string $data, $default = null)
	{
        $file = substr($data, 0, strpos($data, '.'));
        $data = substr($data, strpos($data, '.') + 1, strlen($data));
        $path = absolute_path('config') . $file . '.php';

        return Config::readFile($path, $data, $default);
	}
}

if (!function_exists('__')) {    
    /**
     * return translated word or expression
     *
     * @param  string $expr
     * @param  array $data
     * @return string
     */
    function __(string $expr, array $data = []): string
    {
        return Config::readTranslations($expr, $data);
    }
}

if (!function_exists('get_file_extension')) {	
	/**
	 * get file extension
	 *
	 * @param  string $file
	 * @return string
	 */
	function get_file_extension(string $file): string
	{
		if (empty($file) || strpos($file, '.') === false) {
            return '';
		}
		
		$file_ext = explode('.', $file);
		return $file_ext === false ? '' : end($file_ext);
	}
}

if (!function_exists('get_file_name')) {	
	/**
	 * get file name
	 *
	 * @param  string $file
	 * @return string
	 */
	function get_file_name(string $file): string
	{
		if (empty($file) || strpos($file, '.') === false) {
            return '';
		}
		
		$filename = explode('.', $file);
		return $filename === false ? '' : $filename[0];
	}
}

if (!function_exists('save_log')) {
    /**
	 * save log message to file
	 *
	 * @param  string $message 
	 * @return void
	 */
	function save_log(string $message): void
	{
        if (!Storage::path(config('storage.logs'))->isDir()) {
            Storage::path(config('storage.logs'))->createDir();
        }

        error_log($message);
    }
}

if (!function_exists('env')) {    
    /**
     * get environnement key variable
     *
     * @param  string $key
     * @param  mixed $default
     * @return mixed
     */
    function env(?string $key, $default = null)
    {
        return Config::readEnv($key, $default);
    }
}
