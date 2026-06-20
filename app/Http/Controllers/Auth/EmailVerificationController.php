<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Validation\Validators\Auth\EmailValidator;
use App\UseCases\EmailVerification\VerifyUseCase;
use App\UseCases\Shared\NotifyUseCase;
use Core\Enums\Alert\MessageType;
use Core\Enums\HttpMethod;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

/**
 * Manage email verification link.
 */
class EmailVerificationController extends Controller
{
    #[Route(HttpMethod::GET, '/email/notify')]
    public function index(): BaseResponse
    {
        return $this->viewResponse('auth.notify');
    }

    #[Route(HttpMethod::POST, '/email/notify')]
    public function notify(EmailValidator $validator, NotifyUseCase $useCase): BaseResponse
    {
        $useCase->handle($validator->validated('email'));

        return $this
            ->redirectResponse()
            ->toUrl('/email/notify')
            ->withAlert(MessageType::SUCCESS, __('alert.email_verification_link_sent'));
    }

    #[Route(HttpMethod::GET, '/email/verify')]
    public function verify(VerifyUseCase $verifyUseCase): BaseResponse
    {
        $verifyUseCase->handle();

        return $this
            ->redirectResponse()
            ->toUrl('/login')
            ->withToast(MessageType::SUCCESS, __('alert.email_verified_at'));
    }
}
