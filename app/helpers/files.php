<?php
/**
* Application => TinyMVC (PHP framework based on MVC architecture)
* File        => files.php (miscellaneous files functions)
* Github      => https://github.com/eliseekn/tinymvc
* Copyright   => 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
* Licence     => MIT (https://opensource.org/licenses/MIT)
*/

//manage uploaded file from form
function upload_file(string $field_name, string $destination, bool $multiple, &$filename): bool {
	if (empty($_FILES[$field_name]['name'])) {
        return false;
    }

    try {
        if (!$multiple) {
            $origin = $_FILES[$field_name]['tmp_name'];
            $filename = basename($_FILES[$field_name]['name']);
            $destination = DOCUMENT_ROOT . $destination . '/'. $filename;
            move_uploaded_file($origin, $destination);
        } else {
            for ($i = 0; $i < count($_FILES[$field_name]['name']); $i++) {
                $origin = $_FILES[$field_name]['tmp_name'][$i];
                $filename[] = basename($_FILES[$field_name]['name'][$i]);
                $destination = DOCUMENT_ROOT . $destination . '/'. basename($_FILES[$field_name]['name'][$i]);
                move_uploaded_file($origin, $destination);
            }
        }

        return true;
    } catch (Exception $e) {
        return false;
    }
}

//remove entire directory and all it's content
//https://stackoverflow.com/questions/3338123/how-do-i-recursively-delete-a-directory-and-its-entire-contents-files-sub-dir
function remove_dir(string $directory): void { 
    if (is_dir($directory)) { 
        $objects = scandir($directory);
        
        foreach ($objects as $object) { 
            if ($object != "." && $object != "..") { 
            if (is_dir($directory. DIRECTORY_SEPARATOR .$object) && !is_link($directory."/".$object))
                rrmdir($directory. DIRECTORY_SEPARATOR .$object);
            else
                unlink($directory. DIRECTORY_SEPARATOR .$object); 
            } 
        }
        
        rmdir($directory); 
    } 
}
