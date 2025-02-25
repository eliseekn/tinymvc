<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Carbon\Carbon;
use Core\Database\Model;
use Core\Http\Auth;
use Core\Http\Cookies;
use Core\Http\Request;
use Core\Http\Session;
use Core\Routing\Route;
use Core\Routing\View;
use Core\Support\Config;
use Core\Support\Encryption;
use Core\Support\Storage;
use Faker\Factory;

/*
 * Encryption
 */
if (! function_exists('encrypt')) {
    function encrypt(string $value): string
    {
        return Encryption::encrypt($value);
    }
}

if (! function_exists('decrypt')) {
    function decrypt(string $value): string
    {
        return Encryption::decrypt($value);
    }
}

/*
 * Cookie helper
 */
if (! function_exists('cookies')) {
    function cookies(): Cookies
    {
        return new Cookies();
    }
}

/*
 * Session helper
 */
if (! function_exists('session')) {
    function session(): Session
    {
        return new Session();
    }
}

/*
 * View get content helper
 */
if (! function_exists('view')) {
    function view(string $view, array $data = []): string
    {
        return View::getContent($view, $data);
    }
}

if (! function_exists('storage')) {
    function storage(string $path = APP_ROOT): Storage
    {
        return Storage::path($path);
    }
}

if (! function_exists('auth_attempts_exceeded')) {
    function auth_attempts_exceeded(): bool
    {
        if (! config('security.auth.max_attempts')) {
            return false;
        }

        $unlock_timeout = session()->get('auth_attempts_timeout');
        $attempts = session()->get('auth_attempts');

        if (is_null($attempts) || is_null($unlock_timeout)) {
            return false;
        }

        return $attempts >= config('security.auth.max_attempts') && Carbon::parse($unlock_timeout)->gte(Carbon::now());
    }
}

if (! function_exists('auth')) {
    /**
     * Get authenticated user session data.
     */
    function auth(): Model|false|null
    {
        return Auth::user(request());
    }
}

/*
 * Security utils
 */
if (! function_exists('hash_pwd')) {
    /**
     * Hash password.
     */
    function hash_pwd(string $password): string
    {
        return Encryption::hash($password);
    }
}

if (! function_exists('sanitize')) {
    /**
     * Sanitize html and others scripting languages.
     */
    function sanitize(string $str): string
    {
        $str = stripslashes($str);
        $str = htmlspecialchars($str);

        return strip_tags($str);
    }
}

if (! function_exists('generate_csrf_token')) {
    function generate_csrf_token(): string
    {
        if (session()->has('csrf_token')) {
            $csrf_token = session()->get('csrf_token');
        } else {
            $csrf_token = generate_token();
            session()->create('csrf_token', $csrf_token);
        }

        return $csrf_token;
    }
}


if (! function_exists('request')) {
    /**
     * Generate crsf token html input tag.
     */
    function request(): Request
    {
        return new Request();
    }
}

if (! function_exists('csrf_token_input')) {
    /**
     * Generate crsf token html input tag.
     */
    function csrf_token_input(): string
    {
        return '<input type="hidden" name="_csrf_token" value="' . generate_csrf_token() . '">';
    }
}

if (! function_exists('csrf_token_meta')) {
    /**
     * Generate crsf token html meta tag.
     */
    function csrf_token_meta(): string
    {
        return '<meta name="csrf_token" content="' . generate_csrf_token() . '">';
    }
}

if (! function_exists('method_input')) {
    /**
     * Set custom request method with html input tag.
     */
    function method_input(string $method): string
    {
        return '<input type="hidden" name="_method" value="' . $method . '">';
    }
}

if (! function_exists('valid_csrf_token')) {
    function valid_csrf_token(string $csrf_token): bool
    {
        return hash_equals(session()->get('csrf_token'), $csrf_token);
    }
}

/*
 * Miscellaneous URL utils
 */
if (! function_exists('url')) {
    /**
     * Generate abosulte url.
     */
    function url(string $uri, $params = null): string
    {
        $url = config('app.url');

        if ($url[-1] !== '/') {
            $url .= '/';
        }

        $url .= ltrim($uri, '/');

        $params = is_array($params)
            ? (empty($params) ? '' : implode('/', $params))
            : $params;

        return is_null($params) ? $url : $url . '/' . $params;
    }
}

if (! function_exists('route_uri')) {
    function route_uri(string $name, array $params = []): string
    {
        $uri = '';
        $patterns = ['([a-zA-Z-_]+)', '(\d+)', '([^/]+)'];

        foreach (Route::$routes as $route => $options) {
            if (! empty($options['name'])) {
                if ($name === $options['name']) {
                    $uri = explode(' ', $route, 2)[1];
                }
            }
        }

        if (empty($uri)) {
            throw new Exception('Route name "' . $name . '" is not defined.');
        }

        if (empty($params)) {
            foreach ($patterns as $pattern) {
                if (strpos($uri, '+)?')) {
                    $pattern = "?$pattern?";
                }

                if (strpos($uri, $pattern)) {
                    $uri = substr_replace($uri, '', strpos($uri, $pattern), strlen($pattern));
                }
            }
        } else {
            $params = parse_array($params);

            foreach ($patterns as $pattern) {
                if (strpos($uri, '+)?')) {
                    $pattern = "?$pattern?";

                    if (strpos($uri, $pattern)) {
                        $uri = substr_replace($uri, '', strpos($uri, $pattern), strlen($pattern));
                        next($params);
                        continue;
                    }
                }

                if (strpos($uri, $pattern)) {
                    $uri = substr_replace($uri, $params, strpos($uri, $pattern), strlen($pattern));
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

if (! function_exists('route')) {
    /**
     * Get route absolute url.
     */
    function route(string $name, array $params = []): string
    {
        return url(route_uri($name, $params));
    }
}

if (! function_exists('public_url')) {
    /**
     * Generate public path url.
     */
    function public_url(string $asset): string
    {
        return url('public/' . $asset);
    }
}

if (! function_exists('storage_url')) {
    /**
     * Generate storage url.
     */
    function storage_url(string $path): string
    {
        return url('storage/' . $path);
    }
}

if (! function_exists('current_url')) {
    function current_url(): string
    {
        return url(request()->fullUri());
    }
}

if (! function_exists('url_contains')) {
    function url_contains(string $str): bool
    {
        if (! preg_match('/' . $str . '/', explode('//', current_url())[1])) {
            return false;
        }

        return true;
    }
}

/*
 * Miscellaneous utils
 */
if (! function_exists('real_path')) {
    /**
     * Replace '.' by OS's directory separator.
     */
    function real_path(string $path): string
    {
        return str_replace('.', DIRECTORY_SEPARATOR, $path);
    }
}

if (! function_exists('absolute_path')) {
    /**
     * Generate absolute path.
     */
    function absolute_path(string $path): string
    {
        return APP_ROOT . real_path($path) . DIRECTORY_SEPARATOR;
    }
}

if (! function_exists('slugify')) {
    /**
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

if (! function_exists('generate_token')) {
    function generate_token(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }
}

if (! function_exists('config')) {
    /**
     * Read configuration.
     */
    function config(string $key, $default = null): mixed
    {
        if (! str_contains($key, '.')) {
            $path = absolute_path('config') . $key . '.php';

            return Config::readFile($path, default: $default);
        }

        $file = substr($key, 0, strpos($key, '.'));
        $key = substr($key, strpos($key, '.') + 1, strlen($key));
        $path = absolute_path('config') . $file . '.php';

        return Config::readFile($path, $key, $default);
    }
}

if (! function_exists('__')) {
    /**
     * Translate words and expressions.
     */
    function __(string $expr, array $data = []): string
    {
        return Config::readTranslations($expr, $data);
    }
}

if (! function_exists('get_file_extension')) {
    function get_file_extension(string $file): string
    {
        return pathinfo($file, PATHINFO_EXTENSION);
    }
}

if (! function_exists('get_file_name')) {
    function get_file_name(string $file): string
    {
        return pathinfo($file, PATHINFO_FILENAME);
    }
}

if (! function_exists('get_file_basename')) {
    function get_file_basename(string $file): string
    {
        return pathinfo($file, PATHINFO_BASENAME);
    }
}

if (! function_exists('env')) {
    /**
     * Get environnement variable key.
     */
    function env(string $key, $default = null): mixed
    {
        return Config::readEnv($key, $default);
    }
}

if (! function_exists('parse_array')) {
    /**
     * Convert string to array.
     */
    function parse_array(array|string $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        return [$value];
    }
}

if (! function_exists('carbon')) {
    function carbon($time = null, $tz = null)
    {
        return Carbon::parse($time, $tz);
    }
}

if (! function_exists('faker')) {
    function faker()
    {
        return Factory::create(config('app.lang'));
    }
}

if (! function_exists('init_storage')) {
    function init_storage(): void
    {
        $storage = Storage::path(absolute_path('storage'));

        if (! $storage->isDir()) {
            $storage->createDir();
        }

        if (! $storage->path(config('storage.logs'))->isDir()) {
            $storage->createDir();
        }

        if (! $storage->path(config('storage.cache'))->isDir()) {
            $storage->createDir();
        }

        if (! $storage->path(config('storage.sqlite'))->isDir()) {
            $storage->createDir();
        }

        if (! $storage->path(config('storage.tmp'))->isDir()) {
            $storage->createDir();
        }
    }
}

if (! function_exists('parse_raw_http_request')) {
    /**
     * Parse raw HTTP request data.
     *
     * Pass in $a_data as an array. This is done by reference to avoid copying
     * the data around too much.
     *
     * Any files found in the request will be added by their field name to the
     * $data['files'] array.
     *
     * @ref: http://www.chlab.ch/blog/archives/webdevelopment/manually-parse-raw-http-data-php
     *
     * @return array Associative array of request data
     */
    function parse_raw_http_request(array &$a_data): array
    {
        // read incoming data
        $input = file_get_contents('php://input');

        if (! isset($_SERVER['CONTENT_TYPE'])) {
            // we expect regular puts to contain a query string containing data
            parse_str(urldecode($input), $a_data);

            return $a_data;
        }

        if ($_SERVER['CONTENT_TYPE'] === 'application/json') {
            $a_data = json_decode($input, true);

            if (is_null($a_data)) {
                $a_data = [];
            }

            return $a_data;
        }

        // grab multipart boundary from content type header
        preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);

        // content type is probably regular form-encoded
        if (! count($matches)) {
            // we expect regular puts to contain a query string containing data
            parse_str(urldecode($input), $a_data);

            return $a_data;
        }

        $boundary = $matches[1];

        // split content by boundary and get rid of last -- element
        $a_blocks = preg_split("/-+$boundary/", $input);
        array_pop($a_blocks);

        // loop data blocks
        foreach ($a_blocks as $id => $block) {
            if (empty($block)) {
                continue;
            }

            // you'll have to var_dump $block to understand this and maybe replace \n or \r with a visible char

            // parse uploaded files
            if (str_contains($block, 'application/octet-stream')) {
                // match "name", then everything after "stream" (optional) except for prepending newlines
                if (preg_match('/name="([^"]+)"; filename="([^"]+)"/', $block, $fileMatches)) {
                    $fieldName = $fileMatches[1];
                    $fileName = $fileMatches[2];

                    // Extract file content (skip headers)
                    $fileContent = substr($block, strpos($block, "\r\n\r\n") + 4);
                    $fileContent = substr($fileContent, 0, -2); // Remove trailing CRLF

                    // Save file to tmp directory
                    $tmpFilePath = tempnam(sys_get_temp_dir(), 'php');
                    file_put_contents($tmpFilePath, $fileContent);

                    // Detect MIME type dynamically
                    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
                    $mimeType = finfo_file($fileInfo, $tmpFilePath);
                    finfo_close($fileInfo);

                    // Populate $_FILES array
                    $_FILES[$fieldName] = [
                        'name' => get_file_basename($fileName),
                        'type' => $mimeType,
                        'tmp_name' => $tmpFilePath,
                        'error' => 0,
                        'size' => filesize($tmpFilePath),
                    ];
                }

                $a_data = [];
            }
            // parse all other fields
            else {
                // match "name" and optional value in between newline sequences
                preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
                $a_data[$matches[1]] = $matches[2];
            }
        }

        return $a_data;
    }
}

if (! function_exists('report')) {
    function report(Exception $e): void
    {
        error_log($e->getMessage());
    }
}
