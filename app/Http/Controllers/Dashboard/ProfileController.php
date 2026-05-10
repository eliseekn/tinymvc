<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Middlewares\Authenticated;
use App\Http\Middlewares\EmailVerified;
use App\Http\UseCases\User\DeleteAvatarUseCase;
use App\Http\UseCases\User\UpdateUseCase;
use App\Http\Validation\Validators\UpdateProfileValidator;
use Core\Enums\HttpMethod;
use Core\Enums\RouteParameter;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class ProfileController extends Controller
{
    #[Route(HttpMethod::GET, '/dashboard/profile', [Authenticated::class, EmailVerified::class], 'profile.index')]
    public function index(): void
    {
        $this->render('dashboard.profile');
    }

    #[Route(
        methods: HttpMethod::PATCH,
        uri: '/dashboard/profile/{user}',
        middlewares: [Authenticated::class, EmailVerified::class],
        name: 'profile.update',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function update(UpdateUseCase $useCase, UpdateProfileValidator $validator): void
    {
        $useCase->handle($validator->inputs());
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/dashboard/profile/{user}/avatar',
        middlewares: [Authenticated::class, EmailVerified::class],
        name: 'profile.delete.avatar',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function deleteAvatar(DeleteAvatarUseCase $useCase): void
    {
        $useCase->handle();
    }
}
