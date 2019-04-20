<?php

class Session {

	private $started;

	public function __construct() {
		if (!$this->started) {
			session_start();
			$this->started = true;
		}
	}	

	public function set($data) {
		if (is_array($data)) {
            foreach ($data as $key => $value) {
    			$_SESSION[$key] = $value;
    		}
        }
	}

	public function unset($item) {
		unset($_SESSION[$item]);
	}

	public function get($item) {
		return $_SESSION[$item];
	}

	public function exists($item) {
		return isset($_SESSION[$item]);
	}

	public function destroy() {
		session_unset();
		session_destroy();
	}
}
