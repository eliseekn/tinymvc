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
 * Storage
 * 
 * Manage files and folders
 */
class Storage
{
    /**
     * create new directory
     *
     * @param  string $pathname
     * @param  int $mode creation mode
     * @param  bool $recursive create folders recursively
     * @return bool
     */
    public static function createDir(string $pathname, bool $recursive = false, int $mode = 0777): bool
    {
        return mkdir(PUBLIC_STORAGE . $pathname, $mode, $recursive);
    }
    
    /**
     * create new file or write into
     *
     * @param  string $filename name of file
     * @param  mixed $content content of file
     * @param  bool $append write content at the end of the file
     * @return bool
     */
    public static function writeFile(string $filename, $content, bool $append = false): bool
    {
        $flag = $append ? FILE_APPEND | LOCK_EX : 0;
        $success = file_put_contents(PUBLIC_STORAGE . $filename, $content, $flag);
        return $success === false ? false : true;
    }
    
    /**
     * copy file
     *
     * @param  string $filename name of file
     * @param  string $destination destination path of file
     * @return bool
     */
    public static function copyFile(string $filename, string $destination): bool
    {
        return copy($filename, PUBLIC_STORAGE . $destination);
    } 
    
    /**
     * rename file
     *
     * @param  string $oldname old name of file
     * @param  string $newname new name of file
     * @return bool
     */
    public static function renameFile(string $oldname, string $newname): bool
    {
        return rename(PUBLIC_STORAGE . $oldname, PUBLIC_STORAGE . $newname);
    } 
    
    /**
     * move file
     *
     * @param  string $filename name of file
     * @param  string $destination destination path of file
     * @return bool
     */
    public static function moveFile(string $filename, string $destination): bool
    {
        return move_uploaded_file($filename, PUBLIC_STORAGE . $destination);
    }
    
    /**
     * get file content
     *
     * @param  string $filename name of file
     * @return string
     */
    public static function readFile(string $filename): string
    {
        $data = file_get_contents($filename);
        return $data === false ? '' : $data;
    }
    
    /**
     * check if file exists
     *
     * @param  string $filename name of file
     * @return bool
     */
    public static function isFile(string $filename): bool
    {
        return file_exists(PUBLIC_STORAGE . $filename);
    }

    /**
     * check if folder exists
     *
     * @param  string $pathname
     * @return bool
     */
    public static function isDir(string $pathname): bool
    {
        return is_dir(PUBLIC_STORAGE . $pathname);
    }
    
    /**
     * delete file
     *
     * @param  string $filename
     * @return bool
     */
    public static function deleteFile(string $filename): bool
    {
        return unlink(PUBLIC_STORAGE . $filename);
    }
    
    /**
     * delete directory
     *
     * @param  string $pathname
     * @return bool
     * @link https://stackoverflow.com/questions/3338123/how-do-i-recursively-delete-a-directory-and-its-entire-contents-files-sub-dir
     */
    public static function deleteDir(string $pathname): bool
    {
        if (self::isDir($pathname)) {
            $objects = scandir(PUBLIC_STORAGE . $pathname);
    
            foreach ($objects as $object) {
                if ($object != '.' && $object != '..') {
                    if (
                        self::isDir($pathname . DIRECTORY_SEPARATOR . $object) &&
                        !is_link(PUBLIC_STORAGE . $pathname . DIRECTORY_SEPARATOR . $object)
                    ) {
                        self::deleteDir($pathname . DIRECTORY_SEPARATOR . $object);
                    } else {
                        self::deleteFile($pathname . DIRECTORY_SEPARATOR . $object);
                    }
                }
            }
    
            return rmdir(PUBLIC_STORAGE . $pathname);
        }

        return false;
    }
    
    /**
     * get list of files
     *
     * @param  string $pathname name of path
     * @return array
     */
    public static function getFiles(string $pathname): array
    {
        $results = [];
        $objects = scandir(PUBLIC_STORAGE . $pathname);

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
     * @param  mixed $pathname name of path
     * @return array
     */
    public static function getFolders(string $pathname): array
    {
        $results = [];
        $objects = scandir(PUBLIC_STORAGE . $pathname);

        foreach ($objects as $object) {
            if ($object != '.' && $object != '..' && self::isDir($object)) {
                $results[] = basename($object);
            }
        }

        return $results;
    }
}