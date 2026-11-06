<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
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
        $data = $validator->validated();

        $useCase->handle($data['email'], $data['provider'] ?? 'user');

        return $this->redirectResponse()
            ->toBack()
            ->withAlert(MessageType::SUCCESS, __('alert.password_reset_link_sent'));
    }

    #[Route(HttpMethod::GET, '/password/reset')]
    public function reset(ResetUseCase $useCase): BaseResponse
    {
        $useCase->handle();

        return $this->viewResponse('auth.password.new', [
            'email' => request()->queries()->get('email'),
            'token' => request()->queries()->get('token'),
            'provider' => request()->queries()->get('provider', 'user'),
        ]);
    }

    #[Route(HttpMethod::POST, '/password/update')]
    public function update(UpdatePasswordUseCase $useCase, UpdatePasswordValidator $validator): BaseResponse
    {
        $data = $validator->validated();

        if (! $useCase->handle($data)) {
            return $this->redirectResponse()
                ->toBack()
                ->withAlert(MessageType::ERROR, __('alert.password_not_reset'));
        }

        return $this->redirectResponse()
            ->toUrl(($data['provider'] ?? 'user') === 'admin' ? '/admin/login' : '/login')
            ->withAlert(MessageType::SUCCESS, __('alert.password_reset'));
    }
}
