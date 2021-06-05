<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http;

use Core\Support\Alert;
use Core\System\Cookies;
use Core\System\Session;

/**
 * Handle HTTP redirection
 */
class Redirect
{
    /**
     * url to redirect
     *
     * @var string
     */
    public $url = '';

    /**
     * redirect to url 
     *
     * @param  string $url
     * @return \Core\Http\Redirect
     */
    public function url(string $url): self
    {
        $this->url = url($url);
        return $this;
    }
    
    /**
     * redirect to route
     *
     * @param  string $route
     * @param  mixed $params
     * @return \Core\Http\Redirect
     */
    public function route(string $route, $params = null): self
    {
        $this->url = route($route, $params);
        return $this;
    }

    /**
     * go to previous page
     *
     * @return \Core\Http\Redirect
     */
    public function back(): self
    {
        $history = Session::get('history');

        if (!empty($history)) {
            end($history);
            $this->url = prev($history);
        }

        return $this;
    }
    
    /**
     * redirect to intended uri
     *
     * @param  string $uri
     * @return \Core\Http\Redirect
     */
    public function intended(string $uri): self
    {
        return $this->with('intended', $uri);
    }

    /**
     * perform redirection
     *
     * @param  int $code
     * @return void
     */
    public function go(int $code = 302): void
    {
        exit((new Response())->headers(['Location' => $this->url], $code));
    }
    
    /**
     * redirects and create session data
     *
     * @param  string $key
     * @param  mixed $data
     * @return \Core\Http\Redirect
     */
    public function with(string $key, $data): self
    {
        Session::create($key, $data);
        return $this;
    }
    
    /**
     * redirects and create cookie
     *
     * @param  string $name
     * @param  string $value
     * @param  int $expire in seconds
     * @param  bool $secure
     * @param  string $domain
     * @return \Core\Http\Redirect
     */
    public function withCookie(string $name, string $value, int $expire = 3600, bool $secure = false, string $domain = ''): self
    {
        Cookies::create($name, $value, $expire, $secure, $domain);
        return $this;
    }
    
    /**
     * redirects with errors session data
     *
     * @param  array $errors
     * @return \Core\Http\Redirect
     */
    public function withErrors(array $errors): self
    {
        Session::create('errors', $errors);
        return $this;
    }

    /**
     * redirects with inputs session data
     *
     * @param  array $inputs
     * @return \Core\Http\Redirect
     */
    public function withInputs(array $inputs): self
    {
        Session::create('inputs', $inputs);
        return $this;
    }

    /**
     * redirects with default alert message
     *
     * @param  string $type
     * @param  mixed $message
     * @param  bool $dismiss
     * @return \Core\Http\Redirect
     */
    public function withAlert(string $type, $message, bool $dismiss = true): self
    {
        Alert::default($message, $dismiss)->{$type}();
        return $this;
    }

    /**
     * redirects with toast message
     *
     * @param  string $type
     * @param  mixed $message
     * @return \Core\Http\Redirect
     */
    public function withToast(string $type, $message): self
    {
        Alert::toast($message)->{$type}();
        return $this;
    }
}
