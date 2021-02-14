<?php

/**
 * @copyright 2021 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

use Framework\Http\Request;
use Framework\Http\Response;
use Framework\Routing\Route;

/**
 * Web routes
 */

Route::get('/', ['handler' => 'HomeController@index']);

function getUri() {
    $url = '/admin/resources/medias/search?q=image&page=1';

    /* if (strpos($uri, '&page=') !== false) {
        $uri = substr($uri, strpos($uri, '/'), strpos($uri, '&page='));
    } */

    if (strpos($url, '?') !== false) {
        $uri = substr($url, strpos($url, '/'), strpos($url, '?'));
    }

    //looks for something like "?q="
    if (strpos($url, '?') !== false) {
        $_uri = substr($url, strpos($url, '?'), strlen($url));
    }

    if (strpos($_uri, '&page=') !== false) {
        $q = substr($_uri, strpos($_uri, '?'), -1);
    }
 
    return [$uri, $_uri, $q];
}

Route::get('test', [
    'handler' => function() {
        dd(getUri());

        Response::send('Just to test things');
    }
]);