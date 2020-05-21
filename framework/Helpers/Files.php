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

/**
 * Miscellaneous files utils functions
 */

/**
 * generate absolute file/folder path
 *
 * @param  $path actual relative path
 * @return string
 */
function absolute_path(string $path): string
{
    return DOCUMENT_ROOT . $path;
}

/**
 * remove entire directory and all it's content
 *
 * @param  string $dir directory absolute path
 * @return void
 * 
 * @link https://stackoverflow.com/questions/3338123/how-do-i-recursively-delete-a-directory-and-its-entire-contents-files-sub-dir
 */
function remove_dir(string $dir): void
{
    if (is_dir($dir)) {
        $objects = scandir($dir);

        foreach ($objects as $object) {
            if ($object != '.' && $object != '..') {
                if (
                    is_dir($dir . DIRECTORY_SEPARATOR . $object) &&
                    !is_link($dir . DIRECTORY_SEPARATOR . $object)
                ) {
                    remove_dir($dir . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dir . DIRECTORY_SEPARATOR . $object);
                }
            }
        }

        rmdir($dir);
    }
}
