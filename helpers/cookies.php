<?php

abstract class Cookies {

	public function set($data) {
		if (is_array($data)) {
            foreach ($data as $key => $value) {
    			set_cookie($key, $value, time() + 3600 * 24 * 365);
    		}
        }
	}

	public function unset($item) {
		unset($_COOKIE[$item]);
	}

	public function get($item) {
		return $_COOKIE[$item];
	}

	public function exists($item) {
		return isset($_COOKIE[$item]);
	}

	public function destroy() {
		unset($_COOKIE);
	}
}
