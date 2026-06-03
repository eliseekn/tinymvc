<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\User;

use App\Helpers\FileUploadHelper;
use Core\Database\Model;
use Core\Support\Alert;

final class UpdateUseCase
{
    public function __construct(private readonly FileUploadHelper $fileUploadHelper) {}

    public function handle(array $data, Model $user): void
    {
        $file = request()->files('avatar', ['png', 'jpg', 'jpeg']);

        if (! $this->fileUploadHelper->handle($file)) {
            Alert::toast('Failed to upload avatar image')->error();
            response()->back()->send();
        }

        $data['avatar'] = $this->fileUploadHelper->filename;

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        if (! $user->update($data)) {
            Alert::toast('Failed to update profile')->error();
            response()->back()->send();
        }

        session()->create('user', $user->get());

        Alert::toast('Profile updated')->success();
        response()->back()->send();
    }
}
