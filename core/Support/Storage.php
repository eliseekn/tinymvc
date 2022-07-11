<?php

/**
 * @copyright (2019 - 2022) - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

use ErrorException;

/**
 * Manage files and folders
 */
class Storage
{
    protected static string $path = '';
    
    /**
     * Set storage path
     */
    public static function path(string $path = APP_ROOT): self
    {
        self::$path = $path;
        return new self();
    }
    
    public function getPath(): string
    {
        return self::$path;
    }

    public function addPath(string $path, string $trailling_slash = DS): self
    {
        self::$path .= real_path($path) . $trailling_slash;
        return $this;
    }

    public function file(string $filename): string
    {
        return self::$path .= $filename;
    }

    public function createDir(string $pathname = '', bool $recursive = false, int $mode = 0777): bool
    {
        return mkdir(self::$path . $pathname, $mode, $recursive);
    }
    
    public function writeFile(string $filename, $content, bool $append = false): bool
    {
        if (!$this->isDir()) {
            if (!$this->createDir(recursive: true)) {
                return false;
            }
        }

        $flag = $append ? FILE_APPEND | LOCK_EX : 0;
        $success = file_put_contents(self::$path . $filename, $content, $flag);

        return !($success === false);
    }
    
    public function readFile(string $filename)
    {
        $data = file_get_contents(self::$path . $filename);
        return $data === false ? '' : $data;
    }
    
    public function copyFile(string $filename, string $destination): bool
    {
        return copy(self::$path . $filename, self::$path . $destination);
    } 
    
    public function renameFile(string $oldname, string $newname): bool
    {
        return rename(self::$path . $oldname, self::$path . $newname);
    } 
    
    public function moveFile(string $filename, string $destination): bool
    {
        return $this->renameFile($filename, $destination);
    }
    
    public function isFile(string $filename): bool
    {
        return is_file(self::$path . $filename);
    }

    public function isDir(string $pathname = ''): bool
    {
        return is_dir(self::$path . $pathname);
    }
    
    public function deleteFile(string $filename): bool
    {
        return unlink(self::$path . $filename);
    }
    
    /**
     * @link https://stackoverflow.com/questions/3338123/how-do-i-recursively-delete-a-directory-and-its-entire-contents-files-sub-dir
     */
    public function deleteDir(string $pathname = ''): bool
    {
        if ($this->isDir($pathname)) {
            $objects = scandir(self::$path . $pathname);
            $pathname = empty($pathname) ? $pathname : $pathname . DS;

            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if ($this->isDir($pathname . $object) && !is_link(self::$path . $pathname . $object)) {
                        $this->deleteDir($pathname . $object);
                    } else {
                        $this->deleteFile($pathname . $object);
                    }
                }
            }
    
            return rmdir(self::$path . $pathname);
        }

        return false;
    }

    public function getFiles(): array
    {
        $results = [];
        $objects = $this->getFilesAndFolders();

        foreach ($objects as $object) {
            if ($this->isFile($object)) $results[] = basename($object);
        }

        return $results;
    }

    public function getFolders(): array
    {
        $results = [];
        $objects = $this->getFilesAndFolders();

        foreach ($objects as $object) {
            if ($this->isDir($object)) $results[] = basename($object);
        }

        return $results;
    }

    public function getFilesAndFolders(): array
    {
        $results = [];

        try {
            $objects = scandir(self::$path);
        } catch(ErrorException $e) {
            return $results;
        }

        foreach ($objects as $object) {
            if ($object != '.' && $object != '..') {
                $results[] = $object;
            }
        }

        return $results;
    }
}
