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
 * Sessions management functions
 */

/**
 * create session and set session data
 *
 * @param  string $name name of the session
 * @param  mixed $data data to be stored
 * @return void
 */
function create_session(string $name, $data): void
{
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
  		setcookie(session_name(), session_id(), time() + SESSION_LIFETIME);
	}

	$_SESSION[$name] = $data;
}

/**
 * get session data
 *
 * @param  string $name name of the session
 * @return mixed returns session stored data
 */
function get_session(string $name)
{
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
  		setcookie(session_name(), session_id(), time() + SESSION_LIFETIME);
	}

	return $_SESSION[$name] ?? '';
}

/**
 * check if session exists
 *
 * @param  string $name name of the session
 * @return bool returns true or false
 */
function session_has(string $name): bool
{
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
  		setcookie(session_name(), session_id(), time() + SESSION_LIFETIME);
	}

	return isset($_SESSION[$name]);
}

/**
 * delete session
 *
 * @param  string $name name of the session
 * @return void
 */
function close_session(string $name): void
{
	if (session_status() === PHP_SESSION_NONE) {
		session_start();
  		setcookie(session_name(), session_id(), time() + SESSION_LIFETIME);
	}

	unset($_SESSION[$name]);
}

/**
 * create flash message
 *
 * @param  string $title title of message
 * @param  mixed $content content of message
 * @return void
 */
function create_flash_message(string $title, $content): void
{
	create_session('flash_messages', [
		$title => $content
	]);
}

/**
 * get flash message
 *
 * @return mixed returns message content
 */
function get_flash_messages()
{
	$flash_message = get_session('flash_messages');
	close_session('flash_messages');
	return $flash_message;
}