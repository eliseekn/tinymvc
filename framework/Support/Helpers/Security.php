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

if (!function_exists('escape')) {
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
}

if (!function_exists('hash_string')) {
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
}

if (!function_exists('compare_hash')) {
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
}

if (!function_exists('generate_csrf_token')) {
    /**
     * generate crsf token
     *
     * @return string returns html input with token value
     */
    function generate_csrf_token(): string
    {
        if (session_has('csrf_token')) {
            $csrf_token = get_session('csrf_token');
        } else {
            $csrf_token = bin2hex(random_bytes(32));
            create_session('csrf_token', $csrf_token);
        }

        return '<input type="hidden" name="csrf_token" value="' . $csrf_token . '">';
    }
}

if (!function_exists('is_valid_csrf_token')) {
    /**
     * check if crsf token is valid
     *
     * @param  string $csrf_token token value
     * @return bool
     */
    function is_valid_csrf_token(string $csrf_token): bool
    {
        return hash_equals(get_session('csrf_token'), $csrf_token);
    }
}
