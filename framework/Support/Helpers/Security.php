<?php

/**
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Miscellaneous security utils functions
 */

if (!function_exists('escape')) {
	/**
     * escape html and others scripting languages
     *
     * @param  string $str
     * @return string
     */
    function escape(string $str): string
    {
        $str = stripslashes($str);
        $str = htmlspecialchars($str);
        $str = strip_tags($str);
        return $str;
    }
}

if (!function_exists('generate_csrf_token')) {
    /**
     * generate crsf token
     *
     * @return string
     */
    function generate_csrf_token(): string
    {
        if (session_has('csrf_token')) {
            $csrf_token = get_session('csrf_token');
        } else {
            $csrf_token = bin2hex(random_bytes(32));
            create_session('csrf_token', $csrf_token);
        }

        return $csrf_token;
    }
}

if (!function_exists('csrf_token_input')) {
    /**
     * generate crsf token html input
     *
     * @return string
     */
    function csrf_token_input(): string
    {
        return '<input type="hidden" name="csrf_token" id="csrf_token" value="' . generate_csrf_token() . '">';
    }
}

if (!function_exists('valid_csrf_token')) {
    /**
     * check if crsf token is valid
     *
     * @param  string $csrf_token token value
     * @return bool
     */
    function valid_csrf_token(string $csrf_token): bool
    {
        return hash_equals(get_session('csrf_token'), $csrf_token);
    }
}
