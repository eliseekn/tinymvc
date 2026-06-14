<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\User;

use App\Exceptions\InternalServerException;
use App\Helpers\FileUploadHelper;
use App\Policies\ProfilePolicy;
use Core\Database\Model;

final class UpdateUseCase
{
    public function __construct(private readonly FileUploadHelper $fileUploadHelper) {}

    public function handle(array $data, Model $user): void
    {
        $user->isAuthorized(new ProfilePolicy)->onUpdate();

        $file = request()->files('avatar', ['png', 'jpg', 'jpeg']);

        if (! $this->fileUploadHelper->handle($file)) {
            throw new InternalServerException('Failed to upload avatar image');
        }

        $data['avatar'] = $this->fileUploadHelper->filename;

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        if (! $user->update($data)) {
            throw new InternalServerException('Failed to update profile');
        }

        session()->create('user', $user->get());
    }
}
