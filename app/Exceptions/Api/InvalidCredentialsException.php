<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Exceptionso\Api;

use Core\Enums\HttpCode;
use Core\Exceptions\Interfaces\HasCustomHttpResponse;
use Core\Http\Response\BaseResponse;
use Core\Response\Traits\HttpResponse;
use Exception;

class InvalidCredentialsException extends Exception implements HasCustomHttpResponse
{
    use HttpResponse;

    public function __construct()
    {
        parent::__construct();
    }

    public function httpResponse(): BaseResponse
    {
        return $this->errorJsonResponse('Email or password is incorrect', HttpCode::UNAUTHORIZED);
    }
}
