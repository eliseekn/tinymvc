<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Core\Database\Model;
use Core\Event\EventInterface;
use Core\Exceptions\CoreException;
use Core\Http\Auth;
use Core\Http\Cookies;
use Core\Http\Request;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Route;
use Core\Http\Routing\View;
use Core\Http\Session;
use Core\Support\Config;
use Core\Support\Encryption;
use Core\Support\File;
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

if (! function_exists('bcrypt')) {
    /**
     * Hash password.
     */
    function bcrypt(string $password): string
    {
        return Encryption::hash($password);
    }
}

/*
 * Cookie helper
 */
if (! function_exists('cookies')) {
    function cookies(): Cookies
    {
        return new Cookies;
    }
}

/*
 * Session helper
 */
if (! function_exists('session')) {
    function session(): Session
    {
        return new Session;
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
    // @phpstan-ignore-next-line
    function storage(string $path = APP_ROOT): Storage
    {
        return Storage::setPath($path);
    }
}

if (! function_exists('auth_attempts_exceeded')) {
    function auth_attempts_exceeded(): bool
    {
        if (config('security.auth.max_attempts') === 0) {
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
     * Get authenticated user data.
     */
    function auth(): ?Model
    {
        return Auth::user();
    }
}

/*
 * Security utils
 */
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
    function request(): Request
    {
        return new Request;
    }
}

if (! function_exists('response')) {
    function response(): BaseResponse
    {
        return new BaseResponse;
    }
}

if (! function_exists('csrf_token_input')) {
    /**
     * Generate crsf token html input tag.
     */
    function csrf_token_input(): string
    {
        return '<input type="hidden" name="_csrf_token" value="'.generate_csrf_token().'">';
    }
}

if (! function_exists('csrf_token_meta')) {
    /**
     * Generate crsf token html meta tag.
     */
    function csrf_token_meta(): string
    {
        return '<meta name="csrf_token" content="'.generate_csrf_token().'">';
    }
}

if (! function_exists('method_input')) {
    /**
     * Set custom request method with html input tag.
     */
    function method_input(string $method): string
    {
        return '<input type="hidden" name="_method" value="'.$method.'">';
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

        return is_null($params) ? $url : $url.'/'.$params;
    }
}

if (! function_exists('route_uri')) {
    function route_uri(string $name, array $params = []): string
    {
        $uri = '';
        $routeParams = [];
        $routes = Route::getAll();

        foreach ($routes as $route => $options) {
            if (! empty($options['name']) && $name === $options['name']) {
                $uri = explode(' ', $route, 2)[1];
                $routeParams = $options['parameters'] ?? [];
            }
        }

        if (empty($uri)) {
            throw new CoreException("Route name '$uri' is not defined");
        }

        $uri = preg_replace_callback('/\{([a-zA-Z0-9_-]+)\??\}/', function ($matches) use ($routeParams, $params) {
            $optional = str_contains($matches[0], '?');
            $param = $matches[1];

            if ($optional && ! isset($params[$param])) {
                return '';
            }

            if (! isset($params[$param])) {
                throw new CoreException('Missing required parameter');
            }

            if (! preg_match('/^'.$routeParams[$param].'$/', (string) $params[$param])) {
                throw new CoreException('Invalid parameter type');
            }

            return $params[$param];
        }, $uri);

        return rtrim(preg_replace('#//+#', '/', $uri), '/');
    }
}

if (! function_exists('route')) {
    /**
     * Get absolute route url.
     */
    function route(string $name, array $params = []): string
    {
        return url(route_uri($name, $params));
    }
}

if (! function_exists('route_parameters_to_regex')) {
    function route_parameters_to_regex(string $route, array $routeParams, array &$result): string
    {
        $result = [];

        return preg_replace_callback('/\{([a-zA-Z0-9_-]+)\??\}/', function ($matches) use ($routeParams, &$result) {
            $param = $matches[1];
            $optional = str_contains($matches[0], '?');

            if (! isset($routeParams[$param])) {
                throw new CoreException("No pattern defined for parameter '$param'");
            }

            $result[$param] = null;
            $pattern = $routeParams[$param];

            return $optional ? "?$pattern?" : $pattern;
        }, $route);
    }
}

if (! function_exists('resolve_binding')) {
    /**
     * Resolve route model binding
     */
    function resolve_route_binding(string $route, array $routeParams, array $binding): array
    {
        $params = [];
        $result = [];
        $bindingsResolved = [];

        $route = route_parameters_to_regex($route, $routeParams, $result);

        // Handle HTTP method patterns consistently with Router::match()
        $route = preg_replace('/^([A-Z|]+) /', '(?:$1) ', $route);

        if (! preg_match('#^'.$route.'$#', request()->method().' '.request()->uri(), $matches)) {
            return $params;
        }

        array_shift($matches);
        $paramKeys = array_keys($result);

        foreach ($matches as $index => $value) {
            if ($value !== '') {
                $params[$paramKeys[$index]] = $value;
            }
        }

        foreach ($binding as $key => $value) {
            if (isset($params[$key])) {
                $bindingsResolved[] = [
                    'table' => $value[0],
                    'column' => $value[1],
                    'value' => $params[$key],
                ];
            }
        }

        return $bindingsResolved;
    }
}

if (! function_exists('public_url')) {
    /**
     * Generate public path url.
     */
    function public_url(string $asset): string
    {
        return url('public/'.$asset);
    }
}

if (! function_exists('storage_url')) {
    /**
     * Generate storage url.
     */
    function storage_url(string $path): string
    {
        return url('storage/'.$path);
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
        return preg_match('/'.preg_quote($str, '/').'/i', current_url()) === 1;
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
        // @phpstan-ignore-next-line
        return APP_ROOT.real_path($path).DIRECTORY_SEPARATOR;
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

if (! function_exists('random_string')) {
    /*
     * @link https://www.geeksforgeeks.org/php/generating-random-string-using-php/
     */
    function random_string(int $length, bool $withSpecialCharacters = false): string
    {
        $specialCharacters = '~`!@#$%^&*()_-+={}\|/?.><,;:';
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        if ($withSpecialCharacters) {
            $characters .= $specialCharacters;
        }

        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $j = random_int(0, strlen($characters) - 1);
            $result .= $characters[$j];
        }

        return $result;
    }
}

if (! function_exists('config')) {
    /**
     * Read configuration.
     */
    function config(string $key, $default = null): mixed
    {
        if (! str_contains($key, '.')) {
            $path = absolute_path('config').$key.'.php';

            return Config::readFile($path, default: $default);
        }

        $file = substr($key, 0, strpos($key, '.'));
        $key = substr($key, strpos($key, '.') + 1, strlen($key));
        $path = absolute_path('config').$file.'.php';

        return Config::readFile($path, $key, $default);
    }
}

if (! function_exists('__')) {
    /**
     * Translate words and expressions.
     */
    function __(string $expression, array $data = []): string
    {
        return Config::readTranslations($expression, $data);
    }
}

if (! function_exists('get_file_extension')) {
    /**
     * Get file extension
     */
    function get_file_extension(string $file): string
    {
        return File::getExtension($file);
    }
}

if (! function_exists('get_dirname')) {
    /**
     * Get file dirrectory path
     */
    function get_dirname(string $file): string
    {
        return File::getDirname($file);
    }
}

if (! function_exists('get_filename')) {
    /**
     * Get filename only
     */
    function get_filename(string $file): string
    {
        return File::getName($file);
    }
}

if (! function_exists('get_file_basename')) {
    /**
     * Get filename and extension
     */
    function get_file_basename(string $file): string
    {
        return File::getBasename($file);
    }
}

if (! function_exists('env')) {
    /**
     * Get environnement variable key.
     */
    function env(string $key, $default = null, ?int $filter = null): mixed
    {
        $data = Config::readEnv($key, $default);

        if (! is_null($filter)) {
            return filter_var($data, $filter);
        }

        return $data;
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
    function carbon($time = null, $tz = null): CarbonInterface
    {
        return Carbon::parse($time, $tz);
    }
}

if (! function_exists('faker')) {
    function faker(?string $lang = null)
    {
        return Factory::create(is_null($lang) ? config('app.lang') : $lang);
    }
}

if (! function_exists('dispatch')) {
    function dispatch(object $event): void
    {
        if ($event instanceof EventInterface) {
            $event->dispatch();
        }
    }
}
