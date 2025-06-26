<?php

/**
 * @copyright 2019-2025 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Support;

use Core\Enums\HttpMethod;
use Core\Enums\UploadFileError;

/**
 * Manage uploaded files.
 */
class Uploader
{
    public string $filename = '';

    public function __construct(
        private readonly array $file = [],
        private readonly array $allowed_extensions = []
    ) {}

    public function getOriginalFilename(): string
    {
        return $this->file['name'] ?? '';
    }

    public function getFilename(): string
    {
        return File::getName($this->getOriginalFilename());
    }

    public function getFileBasename(): string
    {
        return File::getBasename($this->getOriginalFilename());
    }

    public function getTempFilename(): string
    {
        return $this->file['tmp_name'] ?? '';
    }

    public function getFileType(): string
    {
        return $this->file['type'] ?? '';
    }

    public function getFileExtension(): string
    {
        return File::getExtension($this->getOriginalFilename());
    }

    public function isAllowed(): bool
    {
        return empty($this->allowed_extensions) || in_array(strtolower($this->getFileExtension()), $this->allowed_extensions);
    }

    public function isEmpty(): bool
    {
        return $this->getError() === UploadFileError::UPLOAD_ERR_NO_FILE;
    }

    public function isUploaded(): bool
    {
        if (request()->method() === HttpMethod::POST) {
            return is_uploaded_file($this->getTempFilename());
        }

        return file_exists($this->getTempFilename());
    }

    public function isOverSized(int $max_size): bool
    {
        return $this->getFileSize() > $max_size;
    }

    public function isUnderSized(int $max_size): bool
    {
        return $this->getFileSize() < $max_size;
    }

    public function isExactSize(int $max_size): bool
    {
        return $this->getFileSize() === $max_size;
    }

    public function getFileSize(): int
    {
        return $this->file['size'] ?? 0;
    }

    public function fileSizeToString(): string
    {
        if ($this->getFileSize() <= 0) {
            return '0 KB';
        }

        $bytes = $this->getFileSize() / 1024;

        return $bytes > 1024
            ? number_format($bytes / 1024, 1).' MB'
            : number_format($bytes, 1).' KB';
    }

    public function getError(): string
    {
        if ($this->file['error'] === UPLOAD_ERR_OK) {
            return UploadFileError::UPLOAD_NO_ERR;
        }

        return match ($this->file['error']) {
            UPLOAD_ERR_INI_SIZE => UploadFileError::UPLOAD_ERR_INI_SIZE,
            UPLOAD_ERR_FORM_SIZE => UploadFileError::UPLOAD_ERR_FORM_SIZE,
            UPLOAD_ERR_PARTIAL => UploadFileError::UPLOAD_ERR_PARTIAL,
            UPLOAD_ERR_NO_FILE => UploadFileError::UPLOAD_ERR_NO_FILE,
            UPLOAD_ERR_NO_TMP_DIR => UploadFileError::UPLOAD_ERR_NO_TMP_DIR,
            UPLOAD_ERR_CANT_WRITE => UploadFileError::UPLOAD_ERR_CANT_WRITE,
            8 => UploadFileError::UPLOAD_ERR_STOPPED_EXT,
            default => UploadFileError::UPLOAD_ERR_UNKNOWN,
        };
    }

    public function save(?string $destination = null, ?string $filename = null): bool
    {
        $destination = $destination ?? config('storage.uploads');
        $this->filename = $filename ?? $this->getOriginalFilename();

        // create destination directory if not exists
        if (! storage($destination)->isDir()) {
            if (! storage($destination)->createDir('', true)) {
                return false;
            }
        }

        if (request()->method() === HttpMethod::POST) {
            return move_uploaded_file($this->getTempFilename(), storage($destination)->file($this->filename));
        }

        return rename($this->getTempFilename(), storage($destination)->file($this->filename));
    }
}
