<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Exceptions;

use Core\Enums\Alert\MessageType;
use Core\Exceptions\Interfaces\HasCustomHttpResponse;
use Core\Http\Response\BaseResponse;
use Core\Http\Response\Traits\HttpResponse;
use Exception;

class VerifyEmailException extends Exception implements HasCustomHttpResponse
{
    use HttpResponse;

    public function __construct()
    {
        parent::__construct();
    }

    public function httpResponse(): BaseResponse
    {
        return $this
            ->redirectResponse()
            ->toUrl('/signup')
            ->withAlert(MessageType::ERROR, __('alert.account_not_found'));
    }
}
