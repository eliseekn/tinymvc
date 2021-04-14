<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Http;

use Framework\Support\Alert;
use Framework\System\Cookies;
use Framework\System\Session;

/**
 * Handle HTTP redirection
 */
class Redirect
{
    /**
     * url to redirect
     *
     * @var string $url
     */
    public $url = '';

    /**
     * redirect to url 
     *
     * @param  string $url
     * @return \Framework\Http\Redirect
     */
    public function url(string $url = '/'): self
    {
        $this->url = $url;
        return $this;
    }
    
    /**
     * redirect to route
     *
     * @param  string $route
     * @param  mixed $params
     * @return \Framework\Http\Redirect
     */
    public function route(string $route, $params = null): self
    {
        $this->url = route_uri($route, $params);
        return $this;
    }

    /**
     * go to previous page
     *
     * @return \Framework\Http\Redirect
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
     * @return \Framework\Http\Redirect
     */
    public function intended(string $uri = '/'): self
    {
        return $this->with('intended', $uri);
    }

    /**
     * perform redirection
     *
     * @return void
     */
    public function go(): void
    {
        redirect_to($this->url);
    }
    
    /**
     * redirects and create session data
     *
     * @param  string $key
     * @param  mixed $data
     * @return \Framework\Http\Redirect
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
     * @param  int $expires in seconds
     * @param  bool $secure
     * @param  string $domain
     * @return \Framework\Http\Redirect
     */
    public function withCookie(string $name, string $value, int $expires = 3600, bool $secure = false, string $domain = ''): self
    {
        Cookies::create($name, $value, $expires, $secure, $domain);
        return $this;
    }
    
    /**
     * redirects with errors session data
     *
     * @param  array $errors
     * @return \Framework\Http\Redirect
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
     * @return \Framework\Http\Redirect
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
     * @return \Framework\Http\Redirect
     */
    public function withAlert(string $type, $message, bool $dismiss = true): self
    {
        Alert::default($message, $dismiss)->$type();
        return $this;
    }

    /**
     * redirects with toast message
     *
     * @param  string $type
     * @param  mixed $message
     * @return \Framework\Http\Redirect
     */
    public function withToast(string $type, $message): self
    {
        Alert::toast($message)->$type();
        return $this;
    }
}
