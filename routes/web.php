<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Database\DB;
use Framework\Routing\View;
use Framework\Http\Response;
use Framework\Routing\Route;
use App\Database\Models\FilesModel;

/**
 * Web routes
 */

Route::get('/', [
    'handler' => function() {
        View::render('index');
    }
]);

Route::get('/home', [
    'handler' => 'HomeController@index'
]);

Route::get('test', [
    'handler' => function() {
        //$result = DB::connection('test')->statement('SELECT * FROM users WHERE active = :active', ['active' => 1]);
        $allowed_extensions = array_merge(FilesModel::TYPE[0], FilesModel::TYPE[1], FilesModel::TYPE[2]);
        $ext = 'jpg';

        $b = in_array(strtolower($ext), $allowed_extensions);

        dd($b)
;        //Response::send();
        //Response::send('Just to test things');
    }
]);