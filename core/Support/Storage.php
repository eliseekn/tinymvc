<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

namespace Core\Support;

/**
 * Manage files and folders
 */
class Storage
{
    protected static $path = '';
    
    /**
     * Set storage path
     */
    public static function path(string $path = APP_ROOT): self
    {
        self::$path = $path;
        return new self();
    }
    
    public function getPath()
    {
        return self::$path;
    }

    public function addPath(string $path): self
    {
        self::$path .= real_path($path) . DS;
        return $this;
    }

    public function file(string $filename)
    {
        return self::$path .= $filename;
    }

    public function createDir(string $pathname = '', bool $recursive = false, int $mode = 0777)
    {
        return mkdir(self::$path . $pathname, $mode, $recursive);
    }
    
    public function writeFile(string $filename, $content, bool $append = false)
    {
        if (!$this->isDir()) {
            if (!$this->createDir('', true)) return false;
        }

        $flag = $append ? FILE_APPEND | LOCK_EX : 0;
        $success = file_put_contents(self::$path . $filename, $content, $flag);
        return $success === false ? false : true;
    }
    
    public function readFile(string $filename)
    {
        $data = file_get_contents(self::$path . $filename);
        return $data === false ? '' : $data;
    }
    
    public function copyFile(string $filename, string $destination)
    {
        return copy(self::$path . $filename, self::$path . $destination);
    } 
    
    public function renameFile(string $oldname, string $newname)
    {
        return rename(self::$path . $oldname, self::$path . $newname);
    } 
    
    public function moveFile(string $filename, string $destination)
    {
        return $this->renameFile($filename, $destination);
    }
    
    public function isFile(string $filename)
    {
        return is_file(self::$path . $filename);
    }

    public function isDir(string $pathname = '')
    {
        return is_dir(self::$path . $pathname);
    }
    
    public function deleteFile(string $filename)
    {
        return unlink(self::$path . $filename);
    }
    
    /**
     * @link https://stackoverflow.com/questions/3338123/how-do-i-recursively-delete-a-directory-and-its-entire-contents-files-sub-dir
     */
    public function deleteDir(string $pathname = '')
    {
        if ($this->isDir($pathname)) {
            $objects = scandir(self::$path . $pathname);
            $pathname = empty($pathname) ? $pathname : $pathname . DS;

            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (
                        $this->isDir($pathname . $object) &&
                        !is_link(self::$path . $pathname . $object)
                    ) {
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
    
    public function getFiles()
    {
        $results = [];
        $objects = scandir(self::$path);

        foreach ($objects as $object) {
            if ($object != '.' && $object != '..' && $this->isFile($object)) {
                $results[] = basename($object);
            }
        }

        return $results;
    }

    public function getFolders()
    {
        $results = [];
        $objects = scandir(self::$path);

        foreach ($objects as $object) {
            if ($object != '.' && $object != '..' && $this->isDir($object)) {
                $results[] = basename($object);
            }
        }

        return $results;
    }
}
