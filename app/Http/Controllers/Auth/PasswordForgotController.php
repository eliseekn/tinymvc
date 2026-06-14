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
use App\Http\Validation\Validators\Auth\UpdatePasswordValidator;
use App\UseCases\PasswordReset\NotifyUseCase;
use App\UseCases\PasswordReset\ResetUseCase;
use App\UseCases\PasswordReset\UpdatePasswordUseCase;
use Core\Enums\Alert\MessageType;
use Core\Enums\HttpMethod;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

/**
 * Manage password forgot.
 */
class PasswordForgotController extends Controller
{
    #[Route(HttpMethod::POST, '/password/notify')]
    public function notify(EmailValidator $validator, NotifyUseCase $useCase): BaseResponse
    {
        $useCase->handle($validator->validated('email'));

        return $this
            ->redirectResponse()
            ->toBack()
            ->withAlert(MessageType::SUCCESS, __('alert.password_reset_link_sent'));
    }

    #[Route(HttpMethod::GET, '/password/reset')]
    public function reset(ResetUseCase $useCase): BaseResponse
    {
        $useCase->handle();

        return $this->viewResponse('auth.password.new', [
            'email' => request()->queries()->get('email'),
        ]);
    }

    #[Route(HttpMethod::POST, '/password/update')]
    public function update(UpdatePasswordUseCase $useCase, UpdatePasswordValidator $validator): BaseResponse
    {
        if (! $useCase->handle($validator->validated())) {
            return $this
                ->redirectResponse()
                ->toBack()
                ->withAlert(MessageType::ERROR, __('alert.password_not_reset'));
        }

        return $this
            ->redirectResponse()
            ->toUrl('/login')
            ->withAlert(MessageType::SUCCESS, __('alert.password_reset'));
    }
}
