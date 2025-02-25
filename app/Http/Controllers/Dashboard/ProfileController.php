<?php

declare(strict_types=1);

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace App\Http\Controllers\Dashboard;

use App\Http\Services\FileUploadService;
use App\Http\UseCases\User\UpdateUseCase;
use App\Http\Validators\UpdateProfileValidator;
use Core\Enums\HttpCode;
use Core\Enums\HttpMethod;
use Core\Routing\Attributes\Route;
use Core\Routing\Controller;
use Core\Support\Alert;
use Core\Support\Storage;

class ProfileController extends Controller
{
    #[Route(HttpMethod::GET, '/dashboard/profile', ['auth', 'verified'], 'dashboard.profile.index')]
    public function index(): void
    {
        $this->render('dashboard.profile');
    }

    #[Route(HttpMethod::PATCH, '/dashboard/profile', ['auth', 'verified'], 'dashboard.profile.update')]
    public function update(UpdateUseCase $useCase, UpdateProfileValidator $validator, FileUploadService $fileUploadService): void
    {
        $file = $this->request->files('avatar', ['png', 'jpg', 'jpeg']);

        if (! $fileUploadService->handle($file, $filename)) {
            Alert::toast('Failed to upload avatar image')->error();
            $this->response->back()->send(HttpCode::INTERNAL_SERVER_ERROR);
        }

        $data = $validator->inputs();
        $data['avatar'] = $filename;

        $user = $useCase->handle($data, auth()->get('email'));

        if (! $user) {
            Alert::toast('Failed to update profile')->error();
        } else {
            session()->create('user', $user->get());
        }

        Alert::toast('Profile updated')->success();
        $this->response->back()->send();
    }

    #[Route(HttpMethod::DELETE, '/dashboard/profile/avatar', ['auth', 'verified'], 'dashboard.profile.delete_avatar')]
    public function deleteAvatar(UpdateUseCase $useCase): void
    {
        $user = $useCase->handle(['avatar' => null], auth()->get('email'));

        if (! $user) {
            Alert::toast('Failed to update profile')->error();
        } else {
            Storage::path(config('storage.uploads'))->deleteFile(auth()->get('avatar'));
            session()->create('user', $user->get());
        }

        $this->response->back()->send();
    }
}
