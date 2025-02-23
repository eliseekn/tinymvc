<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Api\v1;

use App\Database\Models\User;
use App\Http\UseCases\Api\v1\User\GetCollectionUseCase;
use App\Http\UseCases\Api\v1\User\StoreUseCase;
use App\Http\UseCases\User\UpdateUseCase;
use App\Http\Validators\User\StoreValidator;
use App\Http\Validators\User\UpdateValidator;
use Core\Enums\HttpCode;
use Core\Enums\HttpMethod;
use Core\Enums\ResponseStatus;
use Core\Routing\Attributes\Route;
use Core\Routing\Controller;

class UserController extends Controller
{
    #[Route(HttpMethod::GET, 'api/v1/users', ['api'])]
    public function index(GetCollectionUseCase $useCase): void
    {
        $useCase->handle($this->request->queries());
    }

    #[Route(HttpMethod::GET, 'api/v1/users/{id:int}', ['api'])]
    public function show(int $id): void
    {
        $this->jsonResponse(User::find($id)->get());
    }

    #[Route(HttpMethod::POST, 'api/v1/users', ['api', 'admin'])]
    public function store(StoreUseCase $useCase, StoreValidator $validator): void
    {
        $useCase->handle($validator->validated());
    }

    #[Route(HttpMethod::PATCH, 'api/v1/users/{id:int}', ['api', 'admin'])]
    public function update(UpdateUseCase $useCase, UpdateValidator $validator, int $id): void
    {
        $user = User::find($id);

        if (! $user || ! $useCase->handle($validator->validated(), $user->get('email'))) {
            $this->jsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => 'Failed to update user',
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }

        $this->jsonResponse([
            'status' => ResponseStatus::SUCCESS,
            'message' => 'User updated',
            'user' => User::find($id)->get()
        ]);
    }

    #[Route(HttpMethod::DELETE, 'api/v1/users/{id:int}', ['api', 'admin'])]
    public function delete(int $id): void
    {
        $user = User::find($id);

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
