<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Carbon\Carbon;
use Configula\ConfigFactory;
use Framework\Routing\Route;
use Framework\Support\Cookies;
use Framework\Support\Session;
use Framework\Support\Storage;

/**
 * Cookies management functions
 */

if (!function_exists('create_cookie')) {
	/**
	 * create cookie and set value
	 *
	 * @param  string $name
	 * @param  string $value
	 * @param  int $expires in seconds
	 * @param  bool $secure
	 * @param  string $domain
	 * @return bool
	 */
    function create_cookie(string $name, string $value, int $expires = 3600, bool $secure = false, string $domain = ''): bool 
    {
        return Cookies::create($name, $value, $expires, $secure, $domain);
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

if (!function_exists('get_session')) {
	/**
	 * get session data
	 *
	 * @param  string $name
	 * @return mixed returns session stored data
	 */
	function get_session(string $name)
	{
		return Session::get($name);
	}
}

if (!function_exists('session_has')) {
	/**
	 * check if session exists
	 *
	 * @param  string $name
	 * @return bool returns true or false
	 */
	function session_has(string $name): bool
	{
		return Session::has($name);
	}
}

if (!function_exists('close_session')) {
	/**
	 * close session
	 *
	 * @param  array $names
	 * @return void
	 */
	function close_session(array $names): void
	{
		Session::close(implode(',', $names));
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
        $unlock_timeout = Carbon::parse(get_session('auth_attempts_timeout'));
        $auth_attempts = get_session('auth_attempts');
        return !empty($auth_attempts) && ($auth_attempts >= config('security.auth.max_attempts')) && Carbon::now()->lt($unlock_timeout);
    }
}

if (!function_exists('auth')) {
	/**
	 * get authenticated user session data
	 *
	 * @return mixed
	 */
	function auth()
	{
		return get_session('user');
	}
}

/**
 * Miscellaneous security utils functions
 */

if (!function_exists('escape')) {
	/**
     * escape html and others scripting languages
     *
     * @param  string $str
     * @return string
     */
    function escape(string $str): string
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
            $csrf_token = get_session('csrf_token');
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

if (!function_exists('valid_csrf_token')) {
    /**
     * check if crsf token is valid
     *
     * @param  string $csrf_token token value
     * @return bool
     */
    function valid_csrf_token(string $csrf_token): bool
    {
        return hash_equals(get_session('csrf_token'), $csrf_token);
    }
}

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
	function absolute_url(string $url = '/'): string
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

if (!function_exists('in_url')) {	
	/**
	 * check if current url contains specific string
	 *
	 * @param  string $str
	 * @return bool
	 */
	function in_url(string $str): bool
	{
        return preg_match('/' . $str . '/', current_url());
	}
}

/**
 * Miscellaneous utils functions
 */

if (!function_exists('slugify')) {
	/**
	 * generate slug from string with utf8 encoding
	 *
	 * @param  string $str original string
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

if (!function_exists('truncate')) {
	/**
	 * truncate string
	 *
	 * @param  string $str original string
	 * @param  int $length length of truncated string
	 * @param  string $end end of truncated string
	 * @return string
	 */
	function truncate(string $str, int $length, string $end = '[...]'): string
	{
		return mb_strimwidth($str, 0, $length, $end);
	}
}

if (!function_exists('random_string')) {
	/**
	 * random string generator
	 *
	 * @param  int $length
	 * @param  bool $alphanumeric use alphanumeric
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
	 * @param  string $path
	 * @return mixed
	 */
	function config(string $path)
	{
		$config = ConfigFactory::loadPath(APP_ROOT . 'config' . DIRECTORY_SEPARATOR . 'app.php');
		return $config($path, '');
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

if (!function_exists('time_elapsed')) {	
	/**
	 * get time elapsed
	 *
	 * @param  mixed $datetime
	 * @param  int $level
	 * @return string
	 * @link   https://stackoverflow.com/a/18602474
	 */
	function time_elapsed($datetime, int $level = 7): string
	{
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => __('year'),
			'm' => __('month'),
			'w' => __('week'),
			'd' => __('day'),
			'h' => __('hour'),
			'i' => __('minute'),
			's' => __('second'),
		);

		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . (($diff->$k > 1 && $v[-1] !== 's')  ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
	
		$string = array_slice($string, 0, $level);
		return $string ? implode(', ', $string) . __('ago') : __('now');
	}
}

if (!function_exists('__')) {    
    /**
     * return translated word or expression
     *
     * @param  string $expr
     * @param  bool $app_config use application language configuration
     * @return string
     */
    function __(string $expr, bool $app_config = false): string
    {
        $lang = $app_config ? config('app.lang') : auth()->lang;

        $config = ConfigFactory::loadPath('resources/lang/' . $lang . '.php');
		return $config($expr, '');
    }
}

if (!function_exists('generate_pagination')) {
	/**
	 * pagination parameters generator
	 *
	 * @param  int $page
	 * @param  int $total_items
	 * @param  int $items_per_pages
	 * @return array returns pagination parameters
	 */
	function generate_pagination(int $page, int $total_items, int $items_per_pages): array
	{
		//get total number of pages
		$total_pages = $items_per_pages > 0 ? ceil($total_items / $items_per_pages) : 1;

		//get first item of page (offset)
		$first_item = ($page - 1) * $items_per_pages;

		//return paramaters
		return [
			'first_item' => $first_item,
			'total_items' => $total_items,
			'items_per_pages' => $items_per_pages,
			'page' => $page,
			'total_pages' => $total_pages
		];
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

/**
 * Curl HTTP requests
 */

if (!function_exists('curl')) {
    /**
     * send asynchronous HTTP request using curl
     *
     * @param  string $method request method
     * @param  array $urls urls to connect
     * @param  array $data data to send
     * @param  bool $json send data in json format
     * @return array returns headers and body reponse
     * @link   https://niraeth.com/php-quick-function-for-asynchronous-multi-curl/
     *         https://stackoverflow.com/questions/9183178/can-php-curl-retrieve-response-headers-and-body-in-a-single-request
     *         https://www.codexworld.com/post-receive-json-data-using-php-curl/
     *         https://stackoverflow.com/questions/13420952/php-curl-delete-request
     */
    function curl(string $method, array $urls, array $headers = [], ?array $data = null, bool $json = false): array 
    {
        $response_headers = [];
        $response = [];
        $curl_array = [];
        $curl_multi = curl_multi_init(); //init multiple curl processing

        foreach ($urls as $key => $url) {
            $curl_array[$key] = curl_init();
            $curl = $curl_array[$key];

            //set options
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT_MS, 300000);

            //set method
            if (strtoupper($method) !== 'GET') {
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, strtoupper($method));
            }
            
            //set data
            if (!is_null($data)) {
                if ($json) {
                    $data = json_encode($data);
                    $headers[] = 'Content-Type: application/json';
                }
        
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            }

            //set headers
            if (!empty($headers)) {
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            }
            
            //retrieves response headers 
            curl_setopt(
                $curl,
                CURLOPT_HEADERFUNCTION,
                function ($curl, $header) use (&$response_headers, $key) {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) return $len;

                    $response_headers[$key][strtolower(trim($header[0]))][] = trim($header[1]);
                    return $len;
                }
            );

            curl_multi_add_handle($curl_multi, $curl);
        }

        $i = null;

        do {
            curl_multi_exec($curl_multi, $i);
        } while ($i);

        //retrieves response
        foreach ($curl_array as $key => $curl) {
            $response[$key] = curl_multi_getcontent($curl);
            curl_multi_remove_handle($curl_multi, $curl);
        }

        curl_multi_close($curl_multi);

        //return response
        return [
            'headers' => $response_headers,
            'body' => $response
        ];
    }
}