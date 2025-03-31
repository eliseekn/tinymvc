<?php

declare(strict_types=1);

namespace App\Http\Services;

use Core\Support\Uploader;

class FileUploadService
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
