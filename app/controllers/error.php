<?php

require_once "app/core/config.php";
require_once "app/core/controller.php";

class ErrorController extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function error_404() {
		$data = [];
		$data['page_title'] = "Error 404 - Page not found";

		$this->render("error_404", $data);
	}
}
