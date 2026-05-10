<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\UseCases\PasswordReset\NotifyUseCase;
use App\Http\UseCases\PasswordReset\ResetUseCase;
use App\Http\UseCases\PasswordReset\UpdatePasswordUseCase;
use App\Http\Validation\Validators\Auth\EmailValidator;
use App\Http\Validation\Validators\Auth\UpdatePasswordValidator;
use Core\Enums\HttpMethod;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;
use Core\Support\Alert;

/**
 * Manage password forgot.
 */
class PasswordForgotController extends Controller
{
    #[Route(HttpMethod::POST, '/password/notify')]
    public function notify(EmailValidator $validator, NotifyUseCase $useCase): void
    {
        if ($useCase->handle($validator->inputs('email'))) {
            Alert::default(__('alert.password_reset_link_sent'))->success();
        } else {
            Alert::default(__('alert.password_reset_link_not_sent'))->success();
        }

        $this->redirectBack();
    }

    #[Route(HttpMethod::GET, '/password/reset')]
    public function reset(ResetUseCase $useCase): void
    {
        $useCase->handle();
    }

    #[Route(HttpMethod::POST, '/password/update')]
    public function update(UpdatePasswordUseCase $useCase, UpdatePasswordValidator $validator): void
    {
        if (! $useCase->handle($validator->inputs())) {
            Alert::default(__('alert.password_not_reset'))->error();
            response()->back()->send();
        }

        Alert::default(__('alert.password_reset'))->success();
        response()->url('/login')->send();
    }
}
