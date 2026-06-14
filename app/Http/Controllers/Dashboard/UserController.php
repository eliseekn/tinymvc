<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Database\Models\Role;
use App\Database\Models\User;
use App\Http\Middlewares\Authenticated;
use App\Http\Middlewares\CheckIfUserAdmin;
use App\Http\Middlewares\EmailVerified;
use App\Http\Validation\Validators\User\StoreValidator;
use App\Http\Validation\Validators\User\UpdateValidator;
use App\UseCases\User\StoreUseCase;
use App\UseCases\User\UpdateUseCase;
use Core\Database\Model;
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
        uri: '/dashboard/users',
        middlewares: [Authenticated::class, EmailVerified::class],
        name: 'users.index'
    )]
    public function index(): BaseResponse
    {
        return $this->viewResponse('dashboard.users.index', [
            'users' => User::findAllPaginate(),
        ]);
    }

    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard/users/create',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'users.create'
    )]
    public function create(): BaseResponse
    {
        return $this->viewResponse('dashboard.users.create', [
            'roles' => Role::findAll(),
        ]);
    }

    #[Route(
        methods: HttpMethod::POST,
        uri: '/dashboard/users',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'users.store'
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
        uri: '/dashboard/users/{user}/edit',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'users.edit',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function edit(Model $user): BaseResponse
    {
        return $this->viewResponse('dashboard.users.edit', [
            'user' => $user,
            'roles' => Role::findAll(),
        ]);
    }

    #[Route(
        methods: HttpMethod::PATCH,
        uri: '/dashboard/users/{user}',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'users.update',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function update(UpdateUseCase $useCase, UpdateValidator $validator, Model $user): BaseResponse
    {
        $useCase->handle($validator->validated(), $user);

        return $this
            ->redirectResponse()
            ->toBack()
            ->withToast(MessageType::SUCCESS, 'User updated');
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/dashboard/users/{user}/delete',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'users.delete',
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function delete(Model $user): BaseResponse
    {
        if (! $user->delete()) {
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
