<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http;

use Core\Routing\Route;
use Core\Routing\Router;
use Core\Routing\View;
use Core\Support\Cookies;
use Core\Support\Session;
use Core\Exceptions\FileNotFoundException;
use Core\Exceptions\InvalidJsonDataException;
use Core\Exceptions\InvalidResponseDataException;

/**
 * Send HTTP response
 */
class Response
{
    public function __construct(
        public string $uri = '', 
        public $data = null,
        public array $headers = []
    ) {}

    public function addHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);
        return $this;
    }

    public function data(string $data): self
    {
        if (empty($data)) throw new InvalidResponseDataException();

        $this->addHeaders(['Content-Length' => strlen($data)]);
        $this->data = $data;

        return $this;
    }

    public function view(string $view, array $data = []): self
    {
        $this->data = View::getContent($view, $data);
        return $this;
    }

    public function redirect(string $uri): self
    {
        $this->uri = $uri;
        $this->addHeaders(['Location' => url($this->uri)]);

        return $this;
    }

    public function redirectRoute(string $route, $params = null): self
    {
        return $this->redirect(route_uri($route, $params));
    }

    public function redirectBack(): self
    {
        $history = Session::get('history');

        if(!$history) return $this->redirect('/');

        end($history);

        return $this->redirect(prev($history));
    }

    public function redirectController(array $handler, array $params = [])
    {
        $routes = Route::$routes;

        foreach ($routes as $route => $options) {
            if ($options['handler'] === $handler) {
                list(, $uri) = explode(' ', $route, 2);

                Router::dispatchRoute($uri, $options, $params);
            }
        }
    }
    
    public function intended(string $uri): self
    {
        return $this->with('intended', $uri);
    }

    public function with(string $key, $data): self
    {
        Session::create($key, $data);
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
    
    public function withCookie(string $name, string $value, int $expire = 3600, bool $secure = false, string $domain = ''): self
    {
        Cookies::create($name, $value, $expire, $secure, $domain);
        return $this;
    }

    public function withoutCookie(string $name): self
    {
        Cookies::delete($name);
        return $this;
    }

    public function download(string $filename): self
    {
        if (!file_exists($filename)) {
            throw new FileNotFoundException($filename);
        }

        $this->addHeaders([
            'Content-Type' => mime_content_type($filename),
            'Content-Length' => filesize($filename),
			'Content-Disposition' => 'attachment; filename="' . basename($filename) . '"',
			'Cache-Control' => 'no-cache',
			'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);

        ob_clean();
        flush();

        $this->data = readfile($filename);

        return $this;
    }

    public function json(array $data): self
    {
        if (empty($data)) throw new InvalidJsonDataException();

        $data = json_encode($data);

        $this->addHeaders([
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($data),
        ]);

        $this->data = $data;
        
        return $this;
    }

    public function send(int $code = 200)
    {
        if (config('app.env') === 'test') {
            header('Session:' . json_encode($_SESSION));
        }

        http_response_code($code);

        foreach ($this->headers as $key => $value) {
            header($key . ':' . $value);
        }

        if (!empty($this->data)) exit($this->data);

        exit();
    }
}
