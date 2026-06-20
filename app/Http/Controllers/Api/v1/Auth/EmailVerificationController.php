<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Validation\Validators\Auth\EmailValidator;
use App\UseCases\Api\v1\VerifyEmailUseCase;
use App\UseCases\Shared\NotifyUseCase;
use Core\Enums\HttpMethod;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class EmailVerificationController extends Controller
{
    #[Route(HttpMethod::POST, '/api/v1/email/notify')]
    public function notify(EmailValidator $validator, NotifyUseCase $useCase): BaseResponse
    {
        $useCase->handle($validator->validated('email'));

        return $this->successJsonResponse(__('alert.email_verification_link_sent'));
    }

    #[Route(HttpMethod::GET, '/api/v1/email/verify')]
    public function verify(VerifyEmailUseCase $useCase): BaseResponse
    {
        $useCase->handle();

        return $this->successJsonResponse(__('alert.email_verified_at'));
    }
}
