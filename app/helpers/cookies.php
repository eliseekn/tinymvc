<?php

/**
* TinyMVC
*
* MIT License
*
* Copyright (c) 2019, N'Guessan Kouadio Elisée
*
* Permission is hereby granted, free of charge, to any person obtaining a copy
* of this software and associated documentation files (the "Software"), to deal
* in the Software without restriction, including without limitation the rights
* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
* copies of the Software, and to permit persons to whom the Software is
* furnished to do so, subject to the following conditions:
*
* The above copyright notice and this permission notice shall be included in all
* copies or substantial portions of the Software.
*
* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
* AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
* SOFTWARE.
*
* @author: N'Guessan Kouadio Elisée (eliseekn => eliseekn@gmail.com)
*/

require_once "app/core/config.php";

class Cookies {

	public static function set(array $data) {
		$secure = isset($_SERVER["HTTPS"]) ? true : false;

		if (is_array($data)) {
            foreach ($data as $key => $value) {
    			set_cookie(
					$key, //name
					$value, //value
					time() + 3600 * 24 * 7, //expire
					"/", //path
				);
    		}
        }
	}

	public static function unset($item) {
		unset($_COOKIE[$item]);
	}

	public static function get($item) {
		return $_COOKIE[$item];
	}

	public static function exists($item) {
		return isset($_COOKIE[$item]);
	}

	static public function destroy() {
		unset($_COOKIE);
	}
}
