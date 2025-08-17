<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\UseCases\User;

use App\Http\Services\FileUploadService;
use Core\Support\Alert;

final class UpdateUseCase
{
    public function __construct(public FileUploadService $fileUploadService)
    {
    }

    public function handle(array $data): void
    {
        $user = auth();
        $file = request()->files('avatar', ['png', 'jpg', 'jpeg']);

        if (! $file->isEmpty() && ! $this->fileUploadService->handle($file, $filename)) {
            Alert::toast('Failed to upload avatar image')->error();
            response()->back()->send();
        }

        if (isset($filename)) {
            $data['avatar'] = $filename;
        }

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        if (! $user->set($data)->save()) {
            Alert::toast('Failed to update profile')->error();
            response()->back()->send();
        }

        session()->create('user', $user->get());

        Alert::toast('Profile updated')->success();
        response()->back()->send();
    }
}
