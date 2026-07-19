<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\User;

use App\Database\Entities\User;
use App\Exceptions\InternalServerException;
use App\Helpers\FileUploadHelper;

final class UpdateUseCase
{
    public function __construct(private readonly FileUploadHelper $fileUploadHelper) {}

    public function handle(array $data, User $user): void
    {
        $files = request()->files('avatar');

        if (! empty($files)) {
            if (! $this->fileUploadHelper->handle($files[0])) {
                throw new InternalServerException('Failed to upload avatar image');
            }

            $data['avatar'] = $this->fileUploadHelper->filename;
        }

        if (! empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        if (! $user->toModel($data)->save()) {
            throw new InternalServerException('Failed to update profile');
        }
    }
}
