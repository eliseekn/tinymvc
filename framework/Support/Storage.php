<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/TinyMVC
 */

namespace Framework\Support;

/**
 * Manage files and folders
 */
class Storage
{
    /**
     * storage path
     * 
     * @var string
     */
    protected static $path = '';
    
    /**
     * set storage path
     *
     * @param  string $path
     * @return mixed
     */
    public static function path(string $path)
    {
        self::$path = STORAGE[$path] ?? '';
        return new self();
    }

    /**
     * create new directory
     *
     * @param  string $pathname
     * @param  int $mode creation mode
     * @param  bool $recursive create folders recursively
     * @return bool
     */
    public function createDir(string $pathname, bool $recursive = false, int $mode = 0777): bool
    {
        return mkdir(self::$path . $pathname, $mode, $recursive);
    }
    
    /**
     * create new file or write into
     *
     * @param  string $filename
     * @param  mixed $content
     * @param  bool $append write content at the end of the file
     * @return bool
     */
    public function writeFile(string $filename, $content, bool $append = false): bool
    {
        $flag = $append ? FILE_APPEND | LOCK_EX : 0;
        $success = file_put_contents(self::$path . $filename, $content, $flag);
        return $success === false ? false : true;
    }
    
    /**
     * get file content
     *
     * @param  string $filename
     * @return string
     */
    public function readFile(string $filename): string
    {
        $data = file_get_contents(self::$path . $filename);
        return $data === false ? '' : $data;
    }
    
    /**
     * copy file
     *
     * @param  string $filename
     * @param  string $destination
     * @return bool
     */
    public function copyFile(string $filename, string $destination): bool
    {
        return copy($filename, $destination);
    } 
    
    /**
     * rename file
     *
     * @param  string $oldname
     * @param  string $newname
     * @return bool
     */
    public function renameFile(string $oldname, string $newname): bool
    {
        return rename($oldname, $newname);
    } 
    
    /**
     * move file
     *
     * @param  string $filename
     * @param  string $destination
     * @return bool
     */
    public function moveFile(string $filename, string $destination): bool
    {
        return self::renameFile($filename, $destination);
    }
    
    /**
     * check if file exists
     *
     * @param  string $filename
     * @return bool
     */
    public function isFile(string $filename): bool
    {
        return is_file(self::$path . $filename);
    }

    /**
     * check if folder exists
     *
     * @param  string $pathname
     * @return bool
     */
    public function isDir(string $pathname): bool
    {
        return is_dir(self::$path . $pathname);
    }
    
    /**
     * delete file
     *
     * @param  string $filename
     * @return bool
     */
    public function deleteFile(string $filename): bool
    {
        return unlink(self::$path . $filename);
    }
    
    /**
     * delete directory
     *
     * @param  string $pathname
     * @return bool
     * @link https://stackoverflow.com/questions/3338123/how-do-i-recursively-delete-a-directory-and-its-entire-contents-files-sub-dir
     */
    public function deleteDir(string $pathname): bool
    {
        if (self::isDir($pathname)) {
            $objects = scandir(self::$path . $pathname);
    
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (
                        self::isDir($pathname . DIRECTORY_SEPARATOR . $object) &&
                        !is_link(self::$path . $pathname . DIRECTORY_SEPARATOR . $object)
                    ) {
                        self::deleteDir($pathname . DIRECTORY_SEPARATOR . $object);
                    } else {
                        self::deleteFile($pathname . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
    
            return rmdir(self::$path . $pathname);
        }

        return false;
    }
    
    /**
     * get list of files
     *
     * @param  string $pathname
     * @return array
     */
    public function getFiles(string $pathname = ''): array
    {
        $results = [];
        $objects = scandir(self::$path . $pathname);

        foreach ($objects as $object) {
            if ($object != '.' && $object != '..' && self::isFile($object)) {
                $results[] = basename($object);
            }
        }

        return $results;
    }

    /**
     * get list of folders
     *
     * @param  string $pathname
     * @return array
     */
    public function getFolders(string $pathname = ''): array
    {
        $results = [];
        $objects = scandir(self::$path . $pathname);

        foreach ($objects as $object) {
            if ($object != '.' && $object != '..' && self::isDir($object)) {
                $results[] = basename($object);
            }
        }

        return $results;
    }
}
