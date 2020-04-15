<?php
/**
* Application => TinyMVC (PHP framework based on MVC architecture)
* File        => cookies.php (users cookies management functions)
* Github      => https://github.com/eliseekn/tinymvc
* Copyright   => 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
* Licence     => MIT (https://opensource.org/licenses/MIT)
*/

//create cookie and set data
function create_cookie(string $name, string $value) {
	$secure = isset($_SERVER['HTTPS']) ? true : false;

	set_cookie(
		$name, //name
		$value, //value
		time() + 3600 * 24 * 7, //expire
		'/', //path
	);
}

function remove_cookie($item) {
	unset($_COOKIE[$item]);
}

function get_cookie($item) {
	return $_COOKIE[$item];
}

function cookie_exists($item) {
	return isset($_COOKIE[$item]);
}
