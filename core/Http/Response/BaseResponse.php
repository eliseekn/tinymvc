<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Response;

use Core\Enums\AppEnv;
use Core\Enums\HttpCode;
use Core\Exceptions\CoreException;
use Core\Http\Auth;
use Core\Support\Alert;
use Exception;

/**
 * Send HTTP response.
 */
class BaseResponse
{
    public function __construct(
        public string $uri = '',
        public mixed $data = null,
        public array $headers = [],
        public int $statusCode = HttpCode::FOUND
    ) {}

    public function addHeaders(array $headers): self
    {
        $this->headers = array_merge($this->headers, $headers);

        return $this;
    }

    public function setStatusCode(int $code = HttpCode::FOUND): self
    {
        $this->statusCode = $code;

        return $this;
    }

    public function data(string $data): self
    {
        if (empty($data)) {
            throw new CoreException('Invalid response data');
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

        if (! empty($queries)) {
            $query .= '?'.http_build_query($queries);
        }

        $this->uri = ! session()->has('intended') ? $uri : session()->pull('intended');
        $this->addHeaders(['Location' => url($this->uri.rawurldecode($query))]);

        return $this;
    }

    /**
     * @throws Exception
     */
    public function route(string $route, array $params = []): self
    {
        return $this->url(route_uri($route, $params));
    }

    public function back(): self
    {
        $history = session()->get('history');

        if (empty($history)) {
            return $this->url('/');
        }

        end($history);

        if (config('app.env') === AppEnv::TEST && prev($history) === false) {
            return $this->url('/');
        }

        return $this->url(prev($history));
    }

    public function intended(string $uri): self
    {
        return $this->with('intended', $uri);
    }

    public function with(string $key, mixed $data): self
    {
        session()->create($key, $data);

        return $this;
    }

    public function withAlert(string $type, string|array $message, bool $dismiss = true): self
    {
        Alert::default($message, $dismiss)->$type();

        return $this;
    }

    public function withToast(string $type, string|array $message, bool $dismiss = true): self
    {
        Alert::toast($message, $dismiss)->$type();

        return $this;
    }

    public function withErrors(array $errors): self
    {
        return $this->with('errors', $errors);
    }

    public function withInputs(array $inputs): self
    {
        return $this->with('inputs', $inputs);
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

    public function forgetAuth(): self
    {
        Auth::forget();

        return $this;
    }

    public function download(string $filename): self
    {
        if (! file_exists($filename)) {
            throw new CoreException("File $filename not found");
        }

        $this->addHeaders([
            'Content-Type' => mime_content_type($filename),
            'Content-Length' => filesize($filename),
            'Content-Disposition' => 'attachment; filename="'.basename($filename).'"',
            'Cache-Control' => 'no-cache',
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ]);

        ob_clean();
        flush();

        $this->data = readfile($filename);

        return $this;
    }

    public function json(array $data): self
    {
        if (empty($data)) {
            throw new CoreException('Invalid json response data');
        }

        $data = json_encode($data);

        $this->addHeaders([
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($data),
        ]);

        $this->data = $data;

        return $this;
    }

    public function send(): void
    {
        if (config('app.env') === AppEnv::TEST) {
            header('Session:'.json_encode($_SESSION));
        }

        http_response_code($this->statusCode);

        foreach ($this->headers as $key => $value) {
            header($key.':'.$value);
        }

        if (! empty($this->data)) {
            exit($this->data);
        }

        exit();
    }
}
