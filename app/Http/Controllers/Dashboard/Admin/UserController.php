<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\Admin;

use App\Database\Entities\User;
use App\Database\Models\RoleModel;
use App\Database\Models\UserModel;
use App\Http\Middlewares\AuthenticatedAdmin;
use App\Http\Validation\Validators\User\StoreValidator;
use App\Http\Validation\Validators\User\UpdateValidator;
use App\UseCases\User\StoreUseCase;
use App\UseCases\User\UpdateUseCase;
use Core\Enums\Alert\MessageType;
use Core\Enums\HttpMethod;
use Core\Enums\RouteParameter;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class UserController extends Controller
{
    #[Route(
        methods: HttpMethod::GET,
        uri: '/admin/users',
        middlewares: [AuthenticatedAdmin::class],
        name: 'admin.users.index'
    )]
    public function index(): BaseResponse
    {
        return $this->viewResponse('admin.users.index', [
            'users' => UserModel::findAllPaginate(),
        ]);
    }

    #[Route(
        methods: HttpMethod::GET,
        uri: '/admin/users/create',
        middlewares: [AuthenticatedAdmin::class],
        name: 'admin.users.create'
    )]
    public function create(): BaseResponse
    {
        return $this->viewResponse('admin.users.create', [
            'roles' => RoleModel::findAll(),
        ]);
    }

    #[Route(
        methods: HttpMethod::POST,
        uri: '/admin/users',
        middlewares: [AuthenticatedAdmin::class],
        name: 'admin.users.store'
    )]
    public function store(StoreUseCase $useCase, StoreValidator $validator): BaseResponse
    {
        $useCase->handle($validator->validated());

        return $this
            ->redirectResponse()
            ->toBack()
            ->withToast(MessageType::SUCCESS, 'User created');
    }

    #[Route(
        methods: HttpMethod::GET,
        uri: '/admin/users/{user}/edit',
        middlewares: [AuthenticatedAdmin::class],
        name: 'admin.users.edit',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function edit(User $user): BaseResponse
    {
        return $this->viewResponse('admin.users.edit', [
            'user' => $user,
            'roles' => RoleModel::findAll(),
        ]);
    }

    #[Route(
        methods: HttpMethod::PATCH,
        uri: '/admin/users/{user}',
        middlewares: [AuthenticatedAdmin::class],
        name: 'admin.users.update',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function update(UpdateUseCase $useCase, UpdateValidator $validator, User $user): BaseResponse
    {
        $useCase->handle($validator->validated(), $user);

        return $this
            ->redirectResponse()
            ->toBack()
            ->withToast(MessageType::SUCCESS, 'User updated');
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/admin/users/{user}/delete',
        middlewares: [AuthenticatedAdmin::class],
        name: 'admin.users.delete',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function delete(User $user): BaseResponse
    {
        if (! $user->toModel()->delete()) {
            return $this
                ->redirectResponse()
                ->toBack()
                ->withToast(MessageType::ERROR, 'Failed to delete user');
        }

        return $this
            ->redirectResponse()
            ->toBack()
            ->withToast(MessageType::SUCCESS, 'User deleted');
    }
}
