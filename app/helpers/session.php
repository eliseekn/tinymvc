<?php
/**
* Application => TinyMVC (PHP framework based on MVC architecture)
* File        => session.php (users session management functions)
* Github      => https://github.com/eliseekn/tinymvc
* Copyright   => 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
* Licence     => MIT (https://opensource.org/licenses/MIT)
*/

//create session and set session data
function create_session(string $name, $data) {
	session_start();
	$_SESSION[$name] = $data;
}

//get session data
function get_session(string $name) {
	session_start();
	return $_SESSION[$name];
}

//check session data
function session_exists(string $name):bool {
	session_start();
	return isset($_SESSION[$name]);
}

//destroy session
function destroy_session(string $name) {
	session_start();
	unset($_SESSION[$name]);
}
