<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Middlewares\Authenticated;
use App\Http\Middlewares\RedirectAfterLogin;
use App\Http\Middlewares\RememberUser;
use App\Http\Validation\Validators\Auth\LoginValidator;
use App\Http\Validation\Validators\Auth\RegisterValidator;
use App\UseCases\Auth\LoginUseCase;
use App\UseCases\Auth\RegisterUseCase;
use App\UseCases\Shared\NotifyUseCase;
use Core\Enums\Alert\MessageType;
use Core\Enums\HttpMethod;
use Core\Http\Auth;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class AuthController extends Controller
{
    #[Route(
        methods: HttpMethod::GET,
        uri: '/login',
        middlewares: [RememberUser::class, RedirectAfterLogin::class]
    )]
    public function index(): BaseResponse
    {
        return $this->viewResponse('auth.login');
    }

    #[Route(HttpMethod::POST, '/login')]
    public function login(LoginUseCase $useCase, LoginValidator $validator): BaseResponse
    {
        $useCase->handle($validator->validated(), $user);

        return $this
            ->redirectResponse()
            ->toUrl('/dashboard')
            ->withToast(MessageType::SUCCESS, __('alert.welcome', [
                'name' => $user?->getName(),
            ]));
    }

    #[Route(
        methods: HttpMethod::POST,
        uri: '/logout',
        middlewares: [Authenticated::class]
    )]
    public function __invoke(): BaseResponse
    {
        return $this
            ->redirectResponse()
            ->toUrl(config('app.home'))
            ->forgetAuth()
            ->withToast(MessageType::SUCCESS, __('alert.logged_out'));
    }

    #[Route(HttpMethod::GET, middlewares: [RememberUser::class])]
    public function signup(): BaseResponse
    {
        if (! Auth::check()) {
            return $this->viewResponse('auth.signup');
        }

        return $this
            ->redirectResponse()
            ->toUrl(config('app.home'));
    }

    #[Route(HttpMethod::POST)]
    public function register(RegisterUseCase $useCase, NotifyUseCase $notifyUseCase, RegisterValidator $validator): BaseResponse
    {
        $useCase->handle($validator->validated(), $notifyUseCase);

        return $this
            ->viewResponse('auth.login')
            ->withAlert(MessageType::SUCCESS, __('alert.account_created'));
    }
}
