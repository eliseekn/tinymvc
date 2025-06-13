<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Http\Services;

use Core\Support\Uploader;

final class FileUploadService
{
    public function handle(Uploader $file, &$filename): bool
    {
        if (! $file->isUploaded()) {
            return false;
        }

        if (! $file->isAllowed()) {
            return false;
        }

        $filename = slugify($file->getFilename()).'.'.$file->getFileExtension();

        return $file->save(filename: $filename);
    }
}
