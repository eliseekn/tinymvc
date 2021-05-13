<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Framework\Http;

/**
 * Send HTTP responses
 */
class Response
{
    /**
     * send HTTP headers only
     *
     * @param  string|array $name
     * @param  mixed $value
     * @param  int $code
     * @return void
     */
    public function headers($name, $value = null, int $code = 200): void
    {
        http_response_code($code);

        if (is_array($name)) {
            foreach ($name as $k => $v) {
                header($k . ': ' . $v);
            }

            return;
        }

        header($name . ': ' . $value);
    }
    
    /**
     * send HTTP response
     *
     * @param  mixed $body
     * @param  array $headers
     * @param  int $code
     * @return void
     */
    public function send($body, array $headers = [], int $code = 200): void
    {
        if (!isset($body) or empty($body)) {
            return;
        }
        
        http_response_code($code);

        if (!empty($headers)) {
            foreach ($headers as $name => $value) {
                header($name . ': ' . $value);
            }
        }

        $this->headers('Content-Length', strlen($body));

        exit($body);
    }
    
    /**
     * send HTTP response with json body
     *
     * @param  mixed $body
     * @param  array $headers
     * @param  int $code
     * @return void
     */
    public function json($body, array $headers = [], int $code = 200): void
    {
        if (!isset($body) or empty($body)) {
            return;
        }
        
        http_response_code($code);

        if (!empty($headers)) {
            foreach ($headers as $name => $value) {
                header($name . ': ' . $value);
            }
        }

        if (!is_array($body)) {
            $body = [$body];
        }

        $body = json_encode($body);

        $this->headers('Content-Type', 'application/json');
        $this->headers('Content-Length', strlen($body));

        exit($body);
    }
    
    /**
     * send download file response
     *
     * @param  string $filename
     * @param  string|null $base_name
     * @return void
     */
    public function download(string $filename, ?string $base_name = null): void
    {
        if (!file_exists($filename)) {
            return;
        }

        http_response_code(200);

        $filename = is_null($base_name) ? basename($filename) : $base_name;

        $this->headers([
            'Content-Type' => mime_content_type($filename),
            'Content-Length' => filesize($filename),
			'Content-Disposition' => 'attachment; filename="' . $filename . '"',
			'Cache-Control' => 'no-cache',
			'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);

        ob_clean();
        flush();

        exit(readfile($filename));
    }
}
