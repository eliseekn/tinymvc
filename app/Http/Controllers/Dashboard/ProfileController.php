<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Database\Entities\User;
use App\Http\Middlewares\Authenticated;
use App\Http\Middlewares\EmailVerified;
use App\Http\Validation\Validators\UpdateProfileValidator;
use App\UseCases\User\DeleteAvatarUseCase;
use App\UseCases\User\UpdateUseCase;
use Core\Enums\Alert\MessageType;
use Core\Enums\HttpMethod;
use Core\Enums\RouteParameter;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class ProfileController extends Controller
{
    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard/profile',
        middlewares: [Authenticated::class, EmailVerified::class],
        name: 'profile.index'
    )]
    public function index(): BaseResponse
    {
        return $this->viewResponse('dashboard.profile');
    }

    #[Route(
        methods: HttpMethod::PATCH,
        uri: '/dashboard/profile/{user}',
        middlewares: [Authenticated::class, EmailVerified::class],
        name: 'profile.update',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function update(UpdateUseCase $useCase, UpdateProfileValidator $validator, User $user): BaseResponse
    {
        $useCase->handle($validator->validated(), $user);

        return $this
            ->redirectResponse()
            ->toBack()
            ->withToast(MessageType::SUCCESS, 'Profile updated');
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/dashboard/profile/{user}/avatar',
        middlewares: [Authenticated::class, EmailVerified::class],
        name: 'profile.delete.avatar',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function deleteAvatar(DeleteAvatarUseCase $useCase, User $user): BaseResponse
    {
        $useCase->handle($user);

        return $this
            ->redirectResponse()
            ->toBack()
            ->withToast(MessageType::SUCCESS, 'Profile updated');
    }
}
