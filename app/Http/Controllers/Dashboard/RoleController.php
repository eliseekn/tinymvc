<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Database\Entities\Role;
use App\Database\Models\RoleModel;
use App\Http\Middlewares\Authenticated;
use App\Http\Middlewares\CheckIfUserAdmin;
use App\Http\Middlewares\EmailVerified;
use App\Http\Validation\Validators\Role\StoreValidator;
use App\Http\Validation\Validators\Role\UpdateValidator;
use App\UseCases\Role\StoreUseCase;
use App\UseCases\Role\UpdateUseCase;
use Core\Cache\Cache;
use Core\Enums\Alert\MessageType;
use Core\Enums\HttpMethod;
use Core\Enums\RouteParameter;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class RoleController extends Controller
{
    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard/roles',
        middlewares: [Authenticated::class, EmailVerified::class],
        name: 'roles.index'
    )]
    public function index(): BaseResponse
    {
        $data = Cache::read(
            'roles.dashboard',
            RoleModel::findAllPaginate(),
            carbon()->addDay()->timestamp
        );

        return $this->viewResponse('dashboard.roles.index', ['roles' => $data]);
    }

    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard/roles/create',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'roles.create'
    )]
    public function create(): BaseResponse
    {
        return $this->viewResponse('dashboard.roles.create', [
            'roles' => RoleModel::findAll(),
        ]);
    }

    #[Route(
        methods: HttpMethod::POST,
        uri: '/dashboard/roles',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'roles.store'
    )]
    public function store(StoreUseCase $useCase, StoreValidator $validator): BaseResponse
    {
        $useCase->handle($validator->validated());

        return $this
            ->redirectResponse()
            ->toBack()
            ->withToast(MessageType::SUCCESS, 'Role created');
    }

    #[Route(
        methods: HttpMethod::GET,
        uri: '/dashboard/roles/{role}/edit',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'roles.edit',
        parameters: ['role' => RouteParameter::NUMBER],
        bindings: ['role' => ['roles', 'id']]
    )]
    public function edit(Role $role): BaseResponse
    {
        return $this->viewResponse('dashboard.roles.edit', [
            'role' => $role,
            'roles' => RoleModel::findAll(),
        ]);
    }

    #[Route(
        methods: HttpMethod::PATCH,
        uri: '/dashboard/roles/{role}',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'roles.update',
        parameters: ['role' => RouteParameter::NUMBER],
        bindings: ['role' => ['roles', 'id']]
    )]
    public function update(UpdateUseCase $useCase, UpdateValidator $validator, Role $role): BaseResponse
    {
        $useCase->handle($role, $validator->validated());

        return $this
            ->redirectResponse()
            ->toBack()
            ->withToast(MessageType::SUCCESS, 'Role updated');
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/dashboard/roles/{role}/delete',
        middlewares: [Authenticated::class, EmailVerified::class, CheckIfUserAdmin::class],
        name: 'roles.delete',
        parameters: ['role' => RouteParameter::NUMBER],
        bindings: ['role' => ['roles', 'id']]
    )]
    public function delete(Role $role): BaseResponse
    {
        if (! $role->toModel()->delete()) {
            return $this
                ->redirectResponse()
                ->toBack()
                ->withToast(MessageType::ERROR, 'Failed to delete role');
        }

        return $this
            ->redirectResponse()
            ->toBack()
            ->withToast(MessageType::SUCCESS, 'Role deleted');
    }
}
