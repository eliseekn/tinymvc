<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http;

use Core\Http\Responses\DownloadResponse;
use Core\Http\Responses\JsonResponse;
use Core\Http\Responses\BasicResponse;

/**
 * Send HTTP response
 */
class Response
{
    /**
     * @param array|string $name
     */
    public function headers($name, string $value = null, int $code = 200)
    {
        (new BasicResponse())->headers($name, $value, $code);
    }
    
    public function send(string $body, array $headers = [], int $code = 200)
    {
        (new BasicResponse())->send($body, $headers, $code);
    }
    
    public function json(array $body, array $headers = [], int $code = 200)
    {
        (new JsonResponse())->send($body, $headers, $code);
    }
        
    public function download(string $filename)
    {
        (new DownloadResponse())->send($filename);
    }
}
