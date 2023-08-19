<?php

/**
 * @copyright (2019 - 2023) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

/**
 * Manage uploaded files
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
        return get_file_extension($this->getOriginalFilename());
    }
    
    public function isAllowed(): bool
    {
        return empty($this->allowed_extensions) || in_array(strtolower($this->getFileExtension()), $this->allowed_extensions);
    }
    
    public function isUploaded(): bool
    {
        return is_uploaded_file($this->getTempFilename());
    }

    public function isOverSized(int $max_size): bool
    {
        return $this->getFileSize() > $max_size;
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
            ? number_format($bytes/1024, 1) . ' MB'
            : number_format($bytes, 1) . ' KB';
    }
    
    public function getError(): string
    {
        if ($this->file['error'] !== UPLOAD_ERR_OK) {
            return match ($this->file['error']) {
                UPLOAD_ERR_INI_SIZE => 'Uploaded file exceeds the upload_max_filesize directive in php.ini',
                UPLOAD_ERR_FORM_SIZE => 'Uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form',
                UPLOAD_ERR_PARTIAL => 'Uploaded file was only partially uploaded.',
                UPLOAD_ERR_NO_FILE => 'No file was uploaded',
                UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder',
                UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
                8 => 'File upload stopped by extension',
                default => 'Unknown error',
            };
        }

        return 'Unknown error';
    }

    public function save(?string $destination = null, ?string $filename = null): bool
    {
        $destination = $destination ?? config('storage.uploads');
        $this->filename = $filename ?? $this->getOriginalFilename();

        //create destination directory if not exists
        if (!Storage::path($destination)->isDir()) {
            if (!Storage::path($destination)->createDir('', true)) return false;
        }

        return move_uploaded_file($this->getTempFilename(), Storage::path($destination)->file($this->filename));
    }
}
