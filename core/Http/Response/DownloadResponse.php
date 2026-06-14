<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Response;

use Core\Enums\HttpCode;

/**
 * Send Download HTTP response.
 */
class DownloadResponse extends BaseResponse
{
    public function __construct(string $filename, int $code = HttpCode::OK)
    {
        parent::__construct();

        $this->download($filename)->setStatusCode($code);
    }
}
