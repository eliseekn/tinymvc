<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Database\Models\User;
use App\Http\Middlewares\ApiAuth;
use App\Http\Middlewares\CheckIfUserAdmin;
use App\Http\Resources\UserResource;
use App\Http\Validation\Validators\User\StoreValidator;
use App\Http\Validation\Validators\User\UpdateValidator;
use App\UseCases\Api\v1\User\StoreUseCase;
use App\UseCases\Api\v1\User\UpdateUseCase;
use Core\Database\Model;
use Core\Enums\HttpCode;
use Core\Enums\HttpMethod;
use Core\Enums\RouteParameter;
use Core\Http\Response\BaseResponse;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class UserController extends Controller
{
    #[Route(HttpMethod::GET, '/api/v1/users', [ApiAuth::class])]
    public function index(): BaseResponse
    {
        $data = User::findAllPaginate();

        return $this->successJsonResponse(new UserResource($data)->handle());
    }

    #[Route(
        methods: HttpMethod::GET,
        uri: '/api/v1/users/{user}',
        middlewares: [ApiAuth::class],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function show(Model $user): BaseResponse
    {
        return $this->successJsonResponse(new UserResource($user)->handle());
    }

    #[Route(HttpMethod::POST, '/api/v1/users', [ApiAuth::class, CheckIfUserAdmin::class])]
    public function store(StoreUseCase $useCase, StoreValidator $validator): BaseResponse
    {
        $user = $useCase->handle($validator->validated());

        if (! $user) {
            return $this->errorJsonResponse('Failed to create user');
        }

        return $this->successJsonResponse([
            'message' => 'User created',
            'data' => new UserResource($user)->handle(),
        ]);
    }

    #[Route(
        methods: [HttpMethod::PATCH, HttpMethod::PUT],
        uri: '/api/v1/users/{user}',
        middlewares: [ApiAuth::class, CheckIfUserAdmin::class],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function update(UpdateUseCase $useCase, UpdateValidator $validator, Model $user): BaseResponse
    {
        if (! $useCase->handle($validator->validated(), $user)) {
            return $this->errorJsonResponse('Failed to update profile');
        }

        return $this->successJsonResponse([
            'message' => 'Profile updated',
            'data' => new UserResource($user)->handle(),
        ]);
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/api/v1/users/{user}',
        middlewares: [ApiAuth::class, CheckIfUserAdmin::class],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function delete(Model $user): BaseResponse
    {
        if (! $user->delete()) {
            return $this->errorJsonResponse('Failed to delete user');
        }

        return $this->successJsonResponse('User delete', HttpCode::NO_CONTENT);
    }
}
