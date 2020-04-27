<?php

/**
 * TinyMVC
 * 
 * PHP framework based on MVC architecture
 * 
 * @copyright 2019-2020 - N'Guessan Kouadio Elisée (eliseekn@gmail.com)
 * @license MIT (https://opensource.org/licenses/MIT)
 * @link https://github.com/eliseekn/tinymvc
 */

/**
 * Email utils functions
 */

/**
 * send email using built-in PHP mail function
 *
 * @param  string $content
 * @param  string $headers
 * @param  bool $html
 * @return bool
 */
function send_email(array $content, array $headers, bool $is_html = false): bool
{
    if ($is_html) {
        array_unshift($headers, 'MIME-Version: 1.0');
        array_unshift($headers, 'Content-type: text/html; charset=UTF-8');
    }

    return mail(
        $content['to'],
        $content['subject'],
        $content['message'],
        implode("\r\n", $headers)
    );
}
