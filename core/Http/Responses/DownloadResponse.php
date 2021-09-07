<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Http\Responses;

use Exception;

/**
 * Send download file response
 */
class DownloadResponse extends BaseResponse implements ResponseInterface
{
    public function send($filename, array $headers = [], int $code = 200)
    {
        if (!file_exists($filename)) {
            throw new Exception("File $filename does not exists");
        }

        $this->headers(array_merge($headers, [
            'Content-Type' => mime_content_type($filename),
            'Content-Length' => filesize($filename),
			'Content-Disposition' => 'attachment; filename="' . basename($filename) . '"',
			'Cache-Control' => 'no-cache',
			'Pragma' => 'no-cache',
            'Expires' => '0'
        ]), null, $code);

        ob_clean();
        flush();

        exit(readfile($filename));
    }
}
