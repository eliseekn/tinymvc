<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http;

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
        if (empty($data)) {
            throw new InvalidResponseDataException();
        }

        $this->addHeaders(['Content-Length' => strlen($data)]);
        $this->data = $data;

        return $this;
    }

    public function view(string $view, array $data = []): self
    {
        $this->data = view($view, $data);
        return $this;
    }

    public function url(string $uri, array $queries = []): self
    {
        $query = '';

        if (!empty($queries)) {
            $query .= '?' . http_build_query($queries);
        }

        $this->uri = !session()->has('intended') ? $uri : session()->pull('intended');
        $this->addHeaders(['Location' => url($this->uri . rawurldecode($query))]);

        return $this;
    }
    
    public function route(string $route, $params = null): self
    {
        return $this->url(route_uri($route, $params));
    }

    public function back(): self
    {
        $history = session()->get('history');

        if(empty($history)) {
            return $this->url('/');
        }

        end($history);
        return $this->url(prev($history));
    }
    
    public function intended(string $uri): self
    {
        return $this->with('intended', $uri);
    }

    public function with(string $key, $data): self
    {
        session()->create($key, $data);
        return $this;
    }
    
    public function withErrors(array $errors): self
    {
        session()->create('errors', $errors);
        return $this;
    }

    public function withInputs(array $inputs): self
    {
        session()->create('inputs', $inputs);
        return $this;
    }
    
    public function withCookie(string $name, string $value, int $expire = 3600, bool $secure = false, string $domain = ''): self
    {
        cookies()->create($name, $value, $expire, $secure, $domain);
        return $this;
    }

    public function withoutCookie(array|string $names): self
    {
        cookies()->delete($names);
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
        if (empty($data)) {
            throw new InvalidJsonDataException();
        }

        $data = json_encode($data);

        $this->addHeaders([
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($data),
        ]);

        $this->data = $data;
        return $this;
    }

    public function send(int $code = 302): void
    {
        if (config('app.env') === 'test') {
            header('Session:' . json_encode($_SESSION));
        }

        http_response_code($code);

        foreach ($this->headers as $key => $value) {
            header($key . ':' . $value);
        }

        if (!empty($this->data)) {
            exit($this->data);
        }

        exit();
    }
}
