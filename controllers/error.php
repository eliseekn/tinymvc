<?php

require_once "core/config.php";
require_once "core/controller.php";

class ErrorController extends Controller {

	public function __construct() {
		parent::__construct();
	}

	public function error_404() {
		$data = [];
		$data['title'] = "Page not found";

		$this->view->render("error_404", $data);
	}
}
