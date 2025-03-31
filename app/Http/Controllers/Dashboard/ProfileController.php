<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard;

use App\Http\Services\FileUploadService;
use App\Http\UseCases\User\UpdateUseCase;
use App\Http\Validation\Validators\UpdateProfileValidator;
use Core\Enums\HttpMethod;
use Core\Http\Routing\Attributes\Route;
use Core\Http\Routing\Controller;
use Core\Support\Alert;

class ProfileController extends Controller
{
    #[Route(HttpMethod::GET, '/dashboard/profile', ['auth', 'verified'], 'profile.index')]
    public function index(): void
    {
        $this->render('dashboard.profile');
    }

    #[Route(HttpMethod::PATCH, '/dashboard/profile', ['auth', 'verified'], 'profile.update')]
    public function update(UpdateUseCase $useCase, UpdateProfileValidator $validator, FileUploadService $fileUploadService): void
    {
        $data = $validator->inputs();
        $file = $this->request->files('avatar', ['png', 'jpg', 'jpeg']);

        if (! $file->isEmpty() && ! $fileUploadService->handle($file, $filename)) {
            Alert::toast('Failed to upload avatar image')->error();
            $this->response->back()->send();
        }

        if (isset($filename)) {
            $data['avatar'] = $filename;
        }

        $user = $useCase->handle($data, auth()->get('email'));

        if (! $user) {
            Alert::toast('Failed to update profile')->error();
        } else {
            session()->create('user', $user->get());
        }

        Alert::toast('Profile updated')->success();
        $this->response->back()->send();
    }

    #[Route(HttpMethod::DELETE, '/dashboard/profile/avatar', ['auth', 'verified'], 'profile.delete_avatar')]
    public function deleteAvatar(UpdateUseCase $useCase): void
    {
        $user = $useCase->handle(['avatar' => null], auth()->get('email'));

        if (! $user) {
            Alert::toast('Failed to update profile')->error();
        } else {
            storage(config('storage.uploads'))->deleteFile(auth()->get('avatar'));
            session()->create('user', $user->get());
        }

        $this->response->back()->send();
    }
}
