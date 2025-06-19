<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Http\UseCases\Api\v1\User\GetCollectionUseCase;
use App\Http\UseCases\Api\v1\User\StoreUseCase;
use App\Http\UseCases\User\UpdateUseCase;
use App\Http\Validation\Validators\User\StoreValidator;
use App\Http\Validation\Validators\User\UpdateValidator;
use Core\Database\Model;
use Core\Enums\HttpCode;
use Core\Enums\HttpMethod;
use Core\Enums\ResponseStatus;
use Core\Enums\RouteParameter;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class UserController extends Controller
{
    #[Route(HttpMethod::GET, '/api/v1/users', ['api'])]
    public function index(GetCollectionUseCase $useCase): void
    {
        $useCase->handle($this->request->queries());
    }

    #[Route(
        methods: HttpMethod::GET,
        uri: '/api/v1/users/{user}',
        middlewares: ['api'],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function show(?Model $user = null): void
    {
        if (! $user) {
            $this->jsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => 'User not found',
            ], HttpCode::NOT_FOUND);
        }

        $this->jsonResponse($user->get());
    }

    #[Route(HttpMethod::POST, '/api/v1/users', ['api', 'admin'])]
    public function store(StoreUseCase $useCase, StoreValidator $validator): void
    {
        $useCase->handle($validator->inputs());
    }

    #[Route(
        methods: HttpMethod::PATCH,
        uri: '/api/v1/users/{user}',
        middlewares: ['api', 'admin'],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function update(UpdateUseCase $useCase, UpdateValidator $validator, ?Model $user = null): void
    {
        if (! $user || ! $useCase->handle($validator->inputs(), $user->get('email'))) {
            $this->jsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => 'Failed to update user',
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }

        $this->jsonResponse([
            'status' => ResponseStatus::SUCCESS,
            'message' => 'User updated',
            'user' => $user->get(),
        ]);
    }

    #[Route(
        methods: HttpMethod::DELETE,
        uri: '/api/v1/users/{user}',
        middlewares: ['api', 'admin'],
        parameters: ['user' => RouteParameter::NUMBER],
        bindings: ['user' => ['users', 'id']]
    )]
    public function delete(?Model $user = null): void
    {
        if (! $user || ! $user->delete()) {
            $this->jsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => 'Failed to delete user',
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }

        $this->jsonResponse([
            'status' => ResponseStatus::SUCCESS,
            'message' => 'User delete',
        ]);
    }
}
