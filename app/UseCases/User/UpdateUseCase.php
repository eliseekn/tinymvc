<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\UseCases\User;

use App\Database\Entities\User;
use App\Exceptions\InternalServerException;
use App\Helpers\FileUploadHelper;
use Core\Support\UploadedFile;

final class UpdateUseCase
{
    public function __construct(private readonly FileUploadHelper $fileUploadHelper) {}

    public function handle(array $data, User $user): void
    {
        /** @var array<int, UploadedFile> $files */
        $files = request()->files('avatar');

        if (! empty($files) && ! $files[0]->isEmpty()) {
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

        if (! (bool) $user->toModel()->update($data)) {
            throw new InternalServerException('Failed to update profile');
        }
    }
}
