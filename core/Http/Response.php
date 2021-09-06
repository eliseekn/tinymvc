<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http;

use Exception;

/**
 * Send HTTP responses
 */
class Response
{
    public function headers($name, $value = null, int $code = 200)
    {
        if (config('app.env') === 'test') {
            header('Session:' . json_encode($_SESSION));
        }

        http_response_code($code);

        if (!is_array($name)) {
            header($name . ':' . $value);
            return;
        }

        foreach ($name as $k => $v) {
            header($k . ':' . $v);
        }
    }
    
    public function send($body, array $headers = [], int $code = 200)
    {
        if (!isset($body) or empty($body)) {
            return;
        }

        $this->headers(array_merge($headers, [
            'Content-Length' => strlen($body)
        ]), null, $code);

        exit($body);
    }
    
    public function json(array $body, array $headers = [], int $code = 200)
    {
        if (!isset($body) or empty($body)) {
            return;
        }

        $body = json_encode($body);

        $this->headers(array_merge($headers, [
            'Content-Type' => 'application/json',
            'Content-Length' => strlen($body),
        ]), null, $code);

        exit($body);
    }
        
    /**
     * @throws Exception
     */
    public function download(string $filename, ?string $base_name = null)
    {
        if (!file_exists($filename)) {
            throw new Exception("File $filename does not exists");
        }

        http_response_code(200);

        $base_name = is_null($base_name) ? basename($filename) : $base_name;

        $this->headers([
            'Content-Type' => mime_content_type($filename),
            'Content-Length' => filesize($filename),
			'Content-Disposition' => 'attachment; filename="' . $base_name . '"',
			'Cache-Control' => 'no-cache',
			'Pragma' => 'no-cache',
            'Expires' => '0'
        ]);

        ob_clean();
        flush();

        exit(readfile($filename));
    }
}
