<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\UseCases\Api\v1\VerifyEmailUseCase;
use App\Http\UseCases\EmailVerification\NotifyUseCase;
use App\Http\Validation\Validators\Auth\EmailValidator;
use Core\Enums\HttpCode;
use Core\Enums\HttpMethod;
use Core\Enums\ResponseStatus;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class EmailVerificationController extends Controller
{
    #[Route(HttpMethod::POST, '/api/v1/email/notify')]
    public function notify(EmailValidator $validator, NotifyUseCase $useCase): void
    {
        if ($useCase->handle($validator->inputs('email'))) {
            response()->json([
                'status' => ResponseStatus::SUCCESS,
                'message' => __('alert.email_verification_link_sent'),
            ])->send(HttpCode::OK);
        }

        response()->json([
            'status' => ResponseStatus::ERROR,
            'message' => __('alert.email_verification_link_not_sent'),
        ])->send(HttpCode::INTERNAL_SERVER_ERROR);
    }

    #[Route(HttpMethod::GET, '/api/v1/email/verify')]
    public function verify(VerifyEmailUseCase $useCase): void
    {
        $useCase->handle();
    }
}
