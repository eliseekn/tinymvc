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
    public static function createDir(string $pathname, int $mode = 0777, bool $recursive = false): bool
    {
        return mkdir(PUBLIC_STORAGE . $pathname, $mode, $recursive);
    }
    
    /**
     * create new file
     *
     * @param  string $filename name of file
     * @param  mixed $content content of file
     * @param  bool $append overwrite or not file content
     * @return bool
     */
    public static function createFile(string $filename, $content, bool $append = false): bool
    {
        $flag = $append ? FILE_APPEND | LOCK_EX : 0;
        $success = file_put_contents(PUBLIC_STORAGE . $filename, $content, $flag);
        return $success === false ? false : true;
    }
    
    /**
     * copy file
     *
     * @param  string $filename nameo of file
     * @param  string $destination destination path of file
     * @return bool
     */
    public static function copyFile(string $filename, string $destination): bool
    {
        return copy($filename, PUBLIC_STORAGE . $destination);
    } 
    
    /**
     * move file
     *
     * @param  string $filename nameo of file
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
     * @param  string $foldername name of folder
     * @return bool
     */
    public static function isDir(string $foldername): bool
    {
        return is_dir(PUBLIC_STORAGE . $foldername);
    }
    
    /**
     * delete file
     *
     * @param  string $filename name of file
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
     * @return void
     */
    public static function deleteDir(string $pathname): void
    {
        remove_dir(PUBLIC_STORAGE . $pathname);
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
            if ($object != '.' && $object != '..' && $this->isFile($object)) {
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
            if ($object != '.' && $object != '..' && $this->isDir($object)) {
                $results[] = basename($object);
            }
        }

        return $results;
    }
}