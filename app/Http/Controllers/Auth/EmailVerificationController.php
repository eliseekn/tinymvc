<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\UseCases\EmailVerification\NotifyUseCase;
use App\Http\UseCases\EmailVerification\VerifyUseCase;
use App\Http\Validation\Validators\Auth\EmailValidator;
use Core\Enums\HttpMethod;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

/**
 * Manage email verification link.
 */
class EmailVerificationController extends Controller
{
    #[Route(HttpMethod::GET, '/email/notify')]
    public function index(): void
    {
        response()->view('auth.notify')->send();
    }

    #[Route(HttpMethod::POST, '/email/notify')]
    public function notify(EmailValidator $validator, NotifyUseCase $useCase): void
    {
        $useCase->handle($validator->inputs('email'));
    }

    #[Route(HttpMethod::GET, '/email/verify')]
    public function verify(VerifyUseCase $verifyUseCase): void
    {
        $verifyUseCase->handle();
    }
}
