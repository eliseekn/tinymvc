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
use App\Http\UseCases\User\DeleteUseCase;
use App\Http\UseCases\User\UpdateUseCase;
use App\Http\Validation\Validators\UpdateProfileValidator;
use Core\Enums\HttpMethod;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class ProfileController extends Controller
{
    #[Route(HttpMethod::GET, '/dashboard/profile', [Authenticated::class, EmailVerified::class], 'profile.index')]
    public function index(): void
    {
        $this->render('dashboard.profile');
    }

    #[Route(HttpMethod::PATCH, '/dashboard/profile', [Authenticated::class, EmailVerified::class], 'profile.update')]
    public function update(UpdateUseCase $useCase, UpdateProfileValidator $validator): void
    {
        $useCase->handle($validator->inputs());
    }

    #[Route(HttpMethod::DELETE, '/dashboard/profile/avatar', [Authenticated::class, EmailVerified::class], 'profile.delete.avatar')]
    public function deleteAvatar(DeleteUseCase $useCase): void
    {
        $useCase->handle();
    }
}
