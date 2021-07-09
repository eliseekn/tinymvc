<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http;

/**
 * Send HTTP responses
 */
class Response
{
    public function headers($name, $value = null, int $code = 200)
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
    
    public function send($body, array $headers = [], int $code = 200)
    {
        if (!isset($body) or empty($body)) {
            return;
        }

        if (!empty($headers)) {
            $this->headers($headers, null, $code);
        }

        $this->headers('Content-Length', strlen($body), $code);

        exit($body);
    }
    
    public function json($body, array $headers = [], int $code = 200)
    {
        if (!isset($body) or empty($body)) {
            return;
        }

        if (!empty($headers)) {
            $this->headers($headers, null, $code);
        }

        $body = json_encode($body);

        $this->headers([
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($body)
        ], null, $code);

        exit($body);
    }
    
    public function download(string $filename, ?string $base_name = null)
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
