<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace App\Helpers;

use Core\Support\UploadedFile;

final class FileUploadHelper
{
    public string $filename;

    public function handle(UploadedFile $file): bool
    {
        if (! $file->isUploaded()) {
            return false;
        }

        $this->filename = slugify($file->getFilename()).'.'.$file->getFileExtension();

        return $file->save(filename: $this->filename);
    }
}
