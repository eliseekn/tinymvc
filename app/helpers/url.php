<?php
/**
* Application => TinyMVC (PHP framework based on MVC architecture)
* File        => url.php (miscellaneous URL utils functions)
* Github      => https://github.com/eliseekn/tinymvc
* Copyright   => 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
* Licence     => MIT (https://opensource.org/licenses/MIT)
*/

//generate url based on WEB_ROOT
function base_url(string $url): string {
    return WEB_ROOT . $url;
}

//redirect to url
function redirect_url(string $url): void {
	header('Location: '. base_url($url));
	exit();
}
