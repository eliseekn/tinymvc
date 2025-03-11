<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Api\v1;

use App\Database\Models\User;
use App\Http\UseCases\Api\v1\User\GetCollectionUseCase;
use App\Http\UseCases\Api\v1\User\StoreUseCase;
use App\Http\UseCases\User\UpdateUseCase;
use App\Http\Validation\Validators\User\StoreValidator;
use App\Http\Validation\Validators\User\UpdateValidator;
use Core\Enums\HttpCode;
use Core\Enums\HttpMethod;
use Core\Enums\ResponseStatus;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;

class UserController extends Controller
{
    #[Route(HttpMethod::GET, '/api/v1/users/{id?}', ['api'], parameters: ['id' => 'num'])]
    public function index(GetCollectionUseCase $useCase, ?int $id = null): void
    {
        if (is_null($id)) {
            $useCase->handle($this->request->queries());
        }

        $user = User::find($id);

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

    #[Route(HttpMethod::PATCH, '/api/v1/users/{id}', ['api', 'admin'], parameters: ['id' => 'num'])]
    public function update(UpdateUseCase $useCase, UpdateValidator $validator, int $id): void
    {
        $user = User::find($id);

        if (! $user || ! $useCase->handle($validator->inputs(), $user->get('email'))) {
            $this->jsonResponse([
                'status' => ResponseStatus::ERROR,
                'message' => 'Failed to update user',
            ], HttpCode::INTERNAL_SERVER_ERROR);
        }

        $this->jsonResponse([
            'status' => ResponseStatus::SUCCESS,
            'message' => 'User updated',
            'user' => User::find($id)->get(),
        ]);
    }

    #[Route(HttpMethod::DELETE, '/api/v1/users/{id}', ['api', 'admin'], parameters: ['id' => 'num'])]
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
