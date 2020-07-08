<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Support;

/**
 * Uploader
 * 
 * Manage uploaded file
 */
class Uploader
{    
    /**
     * file
     *
     * @var array
     */
    private $file = [];
    
    /**
     * filename destination path
     *
     * @var string
     */
    public $filepath = '';

    /**
     * allowed file extensions
     * 
     * @var array
     */
    public $allowed_extensions = [];
    
    /**
     * __construct
     *
     * @param  array $file
     * @return void
     */
    public function __construct(array $file, array $allowed_extensions)
    {
        $this->file = $file;
        $this->allowed_extensions = $allowed_extensions;
    }
    
    /**
     * get original filename
     *
     * @return string
     */
    public function getOriginalFilename(): string
    {
        return $this->file['name'] ?? '';
    }
    
    /**
     * get temp filename
     *
     * @return string
     */
    public function getTempFilename(): string
    {
        return $this->file['tmp_name'] ?? '';
    }
        
    /**
     * get file type
     *
     * @return string
     */
    public function getFileType(): string
    {
        return $this->file['type'] ?? '';
    }
        
    /**
     * get file extension
     *
     * @return string
     */
    public function getFileExtension(): string
    {
        if (empty($this->getOriginalFilename())) {
            return '';
        }

        $file = explode('.', $this->getOriginalFilename());
        return $file === false ? '' : $file[1];
    }
        
    /**
     * check if file extension is allowed
     *
     * @return bool
     */
    public function isAllowed(): bool
    {
        if (empty($this->allowed_extensions)) {
            return true;
        }

        return in_array(strtolower($this->getFileExtension()), $this->allowed_extensions);
    }
        
    /**
     * check if file is uploaded
     *
     * @return bool
     */
    public function isUploaded(): bool
    {
        return is_uploaded_file($this->getTempFilename());
    }
        
    /**
     * get file size
     *
     * @return int
     */
    public function getFileSize(): int
    {
        return $this->file['size'] ?? 0;
    }
        
    /**
     * convert file size from byte to KB or MB
     *
     * @return int
     */
    public function getFileSizeToString(): string
    {
        if ($this->getFileSize() === 0) {
            return '';
        }

        $bytes = $this->getFileSize() / 1024;

		if ($bytes > 1024) {
			return number_format($bytes/1024, 1) . ' MB';
		} else {
			return number_format($bytes, 1) . ' KB';
		}
    }

    /**
     * move uploaded file
     *
     * @param  string $destination
     * @param  string|null $filename
     * @return bool
     */
    public function moveTo(string $destination, ?string $filename = null): bool
    {
        $filename = is_null($filename) ? $this->getOriginalFilename() : $filename;
        $this->filepath = $destination . DIRECTORY_SEPARATOR . $filename;
        return Storage::moveFile($this->getTempFilename(), $this->filepath);
    }
}
