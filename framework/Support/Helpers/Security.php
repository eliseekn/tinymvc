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
 * Miscellaneous security utils functions
 */

/**
 * escape html and others scripting languages
 *
 * @param  string $str string to escape
 * @return string returns escaped string
 */
function escape(string $str): string
{
    $str = stripslashes($str);
    $str = strip_tags($str);
    $str = htmlspecialchars($str);
    return $str;
}

/**
 * hash string with password_hash() PHP function
 *
 * @param  string $str string to be hashed
 * @return string returns hashed string
 */
function hash_string(string $str): string
{
    return password_hash($str, PASSWORD_DEFAULT);
}

/**
 * compare hashed string with password_verify() PHP function
 *
 * @param  string $str string to be compared
 * @param  string $hash hashed string 
 * @return bool returns true or false
 */
function compare_hash(string $str, string $hash): bool
{
    return password_verify($str, $hash);
}

/**
 * generate crsf token
 *
 * @return string returns token value
 */
function generate_csrf_token(): string
{
    $csrf_token = bin2hex(random_bytes(32));
    create_session('csrf_token', $csrf_token);

    return '<input type="hidden" name="csrf_token" value="' . $csrf_token . '">';
}

/**
 * check if crsf token is valid
 *
 * @param  string $csrf_token token value
 * @return bool
 */
function is_valid_csrf_token(string $csrf_token): bool
{
    if (hash_equals(get_session('csrf_token'), $csrf_token)) {
        close_session('csrf_token');
        return true;
    }

    return false;
}