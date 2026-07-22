<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Exceptions\Api;

use Core\Enums\HttpCode;
use Core\Exceptions\Interfaces\HasCustomHttpResponse;
use Core\Http\Response\BaseResponse;
use Core\Http\Response\Traits\HttpResponse;
use Exception;

class InvalidDataException extends Exception implements HasCustomHttpResponse
{
    use HttpResponse;

    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public function httpResponse(): BaseResponse
    {
        return $this->errorJsonResponse($this->getMessage(), HttpCode::BAD_REQUEST);
    }
}
