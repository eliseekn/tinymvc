<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Middlewares\AuthenticatedAdmin;
use App\Http\Middlewares\RedirectAdminAfterLogin;
use App\Http\Middlewares\RememberAdmin;
use App\Http\Validation\Validators\Auth\AdminLoginValidator;
use App\UseCases\Admin\LoginUseCase;
use Core\Enums\Alert\MessageType;
use Core\Enums\HttpMethod;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class AdminAuthController extends Controller
{
    #[Route(
        methods: HttpMethod::GET,
        uri: '/admin/login',
        middlewares: [RememberAdmin::class, RedirectAdminAfterLogin::class]
    )]
    public function index(): BaseResponse
    {
        return $this->viewResponse('auth.admin.login');
    }

    #[Route(HttpMethod::POST, '/admin/login')]
    public function login(LoginUseCase $useCase, AdminLoginValidator $validator): BaseResponse
    {
        $useCase->handle($validator->validated(), $admin);

        return $this
            ->redirectResponse()
            ->toUrl(config('security.auth.providers.admin.redirect_after_login'))
            ->withToast(MessageType::SUCCESS, __('alert.welcome', [
                'name' => $admin?->getUsername(),
            ]));
    }

    #[Route(
        methods: HttpMethod::POST,
        uri: '/admin/logout',
        middlewares: [AuthenticatedAdmin::class]
    )]
    public function __invoke(): BaseResponse
    {
        return $this
            ->redirectResponse()
            ->toUrl('/admin/login')
            ->forgetAuth('admin')
            ->withToast(MessageType::SUCCESS, __('alert.logged_out'));
    }
}
