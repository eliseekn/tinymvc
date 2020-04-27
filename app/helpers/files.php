<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Miscellaneous files functions
 */

/**
 * manage single or multiple file upload
 *
 * @param  string $field_name
 * @param  string $destination
 * @param  bool $multiple
 * @param  mixed $filename
 * @return bool
 */
function upload_file(string $field_name, string $destination, bool $multiple, &$filename): bool
{
    if (empty($_FILES[$field_name]['name'])) {
        return false;
    }

    try {
        if (!$multiple) {
            $origin = $_FILES[$field_name]['tmp_name'];
            $filename = basename($_FILES[$field_name]['name']);
            $destination = DOCUMENT_ROOT . $destination . '/' . $filename;
            move_uploaded_file($origin, $destination);
        } else {
            for ($i = 0; $i < count($_FILES[$field_name]['name']); $i++) {
                $origin = $_FILES[$field_name]['tmp_name'][$i];
                $filename[] = basename($_FILES[$field_name]['name'][$i]);
                move_uploaded_file($origin, DOCUMENT_ROOT . $destination . '/' . $filename[$i]);
            }
        }

        return true;
    } catch (Exception $e) {
        return false;
    }
}

/**
 * remove entire directory and all it's content
 *
 * @param  string $dir
 * @return void
 * 
 * @link https://stackoverflow.com/questions/3338123/how-do-i-recursively-delete-a-directory-and-its-entire-contents-files-sub-dir
 */
function remove_dir(string $dir): void
{
    if (is_dir($dir)) {
        $objects = scandir($dir);

        foreach ($objects as $object) {
            if ($object != "." && $object != "..") {
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
