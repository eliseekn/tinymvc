<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Routing\Route;
use Framework\Support\Storage;

/**
 * Web routes
 */

Route::get('/', ['handler' => 'HomeController@index']);

Route::get('test', [
    'handler' => function () {
        foreach (Storage::path(config('storage.migrations'))->getFiles() as $file) {
            $migration = get_file_name($file);
            $new_file = $migration . date('_YmdHis') . '.' . get_file_extension($file);

            Storage::path(config('storage.migrations'))->moveFile($file, $new_file);
        }
    }
]);