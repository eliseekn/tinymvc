<?php

class ErrorController {

	public function error_404() {
		$data['page_title'] = "Error 404 - Page not found";
		$data['page_description'] = "The page you've requested doesn't exists on this server";

		load_template('error_404', 'main', $data);
	}
}
