<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

use Core\Support\Storage;

/**
 * Manage uploaded files
 */
class Uploader
{    
    private $file = [];
    public $filename = '';
    private $allowed_extensions = [];

    public function __construct(array $file, array $allowed_extensions)
    {
        $this->file = $file;
        $this->allowed_extensions = $allowed_extensions;
    }
    
    public function getOriginalFilename()
    {
        return $this->file['name'] ?? '';
    }
    
    public function getTempFilename()
    {
        return $this->file['tmp_name'] ?? '';
    }
    
    public function getFileType()
    {
        return $this->file['type'] ?? '';
    }
    
    public function getFileExtension()
    {
        return get_file_extension($this->getOriginalFilename());
    }
    
    public function isAllowed(): bool
    {
        return empty($this->allowed_extensions) ? true 
            : in_array(strtolower($this->getFileExtension()), $this->allowed_extensions);
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
    
    public function fileSizeToString()
    {
        if ($this->getFileSize() <= 0) return '0 KB';

        $bytes = $this->getFileSize() / 1024;

        return $bytes > 1024 
            ? number_format($bytes/1024, 1) . ' MB' 
            : number_format($bytes, 1) . ' KB';
    }
    
    public function getError()
    {
        if ($this->file['error'] != UPLOAD_ERR_OK) {
            switch($this->file['error']) {
                case UPLOAD_ERR_INI_SIZE: return 'Uploaded file exceeds the upload_max_filesize directive in php.ini';
                case UPLOAD_ERR_FORM_SIZE: return 'Uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
                case UPLOAD_ERR_PARTIAL: return 'Uploaded file was only partially uploaded.';
                case UPLOAD_ERR_NO_FILE: return 'No file was uploaded';
                case UPLOAD_ERR_NO_TMP_DIR: return 'Missing a temporary folder';
                case UPLOAD_ERR_CANT_WRITE: return 'Failed to write file to disk';
                case 8: return 'File upload stopped by extension';
                default: return 'Unknow error';
            }
        }
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
