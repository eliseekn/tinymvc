<?php

require_once "config.php";

class View {

	public function render($page, $data) {
		if (is_array($data)) {
			extract($data);
		}

		require_once "views/". WEB_THEME . "/$page.php";
	}
}
