<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http;

use Core\Support\Cookies;
use Core\Support\Session;
use Core\Http\Response\Response;

/**
 * Handle HTTP redirections
 */
class Redirect extends Response
{
    public $uri = '';

    public function to(string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }
    
    public function route(string $route, $params = null): self
    {
        $this->uri = route_uri($route, $params);
        return $this;
    }

    public function back(): self
    {
        $history = Session::get('history');

        if (!empty($history)) {
            end($history);
            $this->uri = prev($history);
        }

        return $this;
    }
    
    public function intended(string $uri): self
    {
        return $this->with('intended', $uri);
    }

    public function go(int $code = 302)
    {
        exit($this->headers('Location', url($this->uri), $code));
    }
    
    public function with(string $key, $data): self
    {
        Session::create($key, $data);
        return $this;
    }
    
    public function withCookie(string $name, string $value, int $expire = 3600, bool $secure = false, string $domain = ''): self
    {
        Cookies::create($name, $value, $expire, $secure, $domain);
        return $this;
    }
    
    public function withErrors(array $errors): self
    {
        Session::create('errors', $errors);
        return $this;
    }

    public function withInputs(array $inputs): self
    {
        Session::create('inputs', $inputs);
        return $this;
    }
}
