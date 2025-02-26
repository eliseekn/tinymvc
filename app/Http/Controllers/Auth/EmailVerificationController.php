<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\UseCases\EmailVerification\VerifyUseCase;
use App\Http\UseCases\User\UpdateUseCase;
use Core\Enums\HttpMethod;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

/**
 * Manage email verification link.
 */
class EmailVerificationController extends Controller
{
    #[Route(HttpMethod::GET, '/email/verify')]
    public function __invoke(VerifyUseCase $verifyUseCase, UpdateUseCase $updateUseCase): void
    {
        $verifyUseCase->handle($updateUseCase);
    }
}
