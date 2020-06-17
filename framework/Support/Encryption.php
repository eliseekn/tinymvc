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

namespace Framework\Support;

/**
 * Encryption
 * 
 * String encryption/decryption
 */
class Encryption
{    
    /**
     * cipher method
     *
     * @var string
     */
    public static $cipher_method = 'aes-128-ctr';
    
    /**
     * encrypt
     *
     * @param  string $str
     * @return mixed false or encrypted string
     */
    public static function encrypt(string $str)
    {
        $enc_key = openssl_digest(ENC_KEY, 'SHA256', TRUE);
        $enc_iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::$cipher_method));
        return openssl_encrypt($str, self::$cipher_method, $enc_key, 0, $enc_iv) . '::' . bin2hex($enc_iv);
    }
    
    /**
     * decrypt
     *
     * @param  string $str
     * @return mixed false or decrypted string
     */
    public static function decrypt(string $enc_str)
    {
        list($str, $enc_iv) = explode('::', $enc_str);
        $enc_key = openssl_digest(ENC_KEY, 'SHA256', TRUE);
        return openssl_decrypt($str, self::$cipher_method, $enc_key, 0, hex2bin($enc_iv));
    }
}
