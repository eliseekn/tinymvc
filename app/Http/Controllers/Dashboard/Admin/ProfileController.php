<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Admin;

use App\Database\Entities\Admin;
use App\Http\Middlewares\AuthenticatedAdmin;
use App\Http\Validation\Validators\Admin\UpdateProfileValidator;
use App\UseCases\Admin\DeleteAvatarUseCase;
use App\UseCases\Admin\UpdateProfileUseCase;
use Core\Enums\Alert\MessageType;
use Core\Enums\HttpMethod;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class ProfileController extends Controller
{
    #[Route(
        methods: HttpMethod::GET,
        uri: '/admin/profile',
        middlewares: [AuthenticatedAdmin::class],
        name: 'admin.profile.index'
    )]
    public function index(): BaseResponse
    {
        return $this->viewResponse('admin.profile');
    }

    #[Route(
        methods: HttpMethod::PATCH,
        uri: '/admin/profile',
        middlewares: [AuthenticatedAdmin::class],
        name: 'admin.profile.update'
    )]
    public function update(UpdateProfileUseCase $useCase, UpdateProfileValidator $validator): BaseResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin');

        $useCase->handle($validator->validated(), $admin);

        return $this
            ->redirectResponse()
            ->toBack()
            ->withToast(MessageType::SUCCESS, 'Profile updated');
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/admin/profile/avatar',
        middlewares: [AuthenticatedAdmin::class],
        name: 'admin.profile.delete.avatar'
    )]
    public function deleteAvatar(DeleteAvatarUseCase $useCase): BaseResponse
    {
        /** @var Admin $admin */
        $admin = auth('admin');

        $useCase->handle($admin);

        return $this
            ->redirectResponse()
            ->toBack()
            ->withToast(MessageType::SUCCESS, 'Avatar deleted');
    }
}
